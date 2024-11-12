// Bind device data for device show page
async function bindDeviceData(device) {
    let deviceData,online,full,empty;
    await getDeviceData(device).then(data => deviceData = data);
    console.log(deviceData);
    if(typeof deviceData === 'object' && Object.keys(deviceData).length > 0){
        online = deviceData?.cabinet?.online ?? false;
        full = deviceData?.cabinet?.busySlots ?? 0;
        empty = deviceData?.cabinet?.emptySlots ?? 0;
    }

    // Remove loaders
    // device.querySelectorAll(".spinner-grow").forEach(el => el.style.display = "none");

    document.querySelector(".device-status .online").style.display = online == 1 ? "flex" : "none";
    document.querySelector(".device-status .offline").style.display = online == 1 ? "none" : "flex";
    document.querySelector(".filled-batteries .num").innerHTML = online == 1 ? full : 0;
    document.querySelector(".empty-batteries .num").innerHTML = online == 1 ? empty : 0;  

    // Handle Batteries And Slots data
    const slots = await getSlotsInfo(device); // array
    if(slots.length > 0){
        slots.forEach((slot) => {
            const slotElement = document.querySelector(`div#slot-${slot.Slot_Num}`);
            slotElement.addEventListener("click",() => { prepareSlotActions(device); });
            // Slot filled   
            if(slot.Battery_Exist){
                slotElement.querySelector("div.battery").style.backgroundColor = slot.Erorr_Number != 0 ? "#ff000085" : "var(--background-color)";
                slotElement.querySelector("input[type='radio']").removeAttribute("disabled");
                slotElement.querySelector("input[type='radio']").style.borderColor = slot.Erorr_Number != 0 ? "#ff000085" : "var(--background-color)";
                
                // Error Slots 
                if(slot.Erorr_Number != '0'){
                    slotElement.setAttribute('slot-err',true);
                }


            }
      });
    }

}

// Get Slots Info
async function getSlotsInfo(device) {
    if (!device) {
        throw new Error('Device ID is required');
    }
    let slots;

    // Fetch slots data
    await $.ajax({
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: `/dashboard/slots`,
        data: { device },
        success: function (data) {
            if (data) {
                slots = data;
                return;
            }
        }
    });

return slots ?? {};
}

// device Operation request
async function deviceOperation(device,operation,slotNum) {
        if (!device || !operation) {
            throw new Error('Device ID And operation type are required');
        }
        refreshDevice(device);

        let returnData;
        // Fetch device data
        await $.ajax({
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: `/dashboard/device-operation`,
            data: {
                device,operation,slotNum
            },
            success: function (data) {
                if (data) {
                    console.log(data)
                    returnData = data;
                    return;
                }
            }
        });
        return returnData ?? {};
}

// eject batteries
async function ejectBattery(device,slotNum,refresh=true) {
    if (!device || !slotNum) {
        throw new Error('Device ID And slot Number are required');
    }
    refreshDevice(device)

    let returnData;
    // Fetch device data
    await $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: `/dashboard/eject-battery`,
        data: { device,slotNum },

        success: function (data) {
            if (data) {
                console.log(data)
                returnData = data;
                return;
            }
        }
    });

    if(refresh) bindDeviceData(device);

    return returnData ?? {};
}

// Eject All batteries
// function ejectAllbatteries(device) {
//     // catch nonexist of device id
//     if (!device) {
//         throw new Error('Device ID is required');
//     }

//     // Eject batteries 
//     const batteries = document.querySelectorAll('.slot');
//     const returnData = [];
//     batteries.forEach(battery => {
//         const slotNum = battery.querySelector('input[type="radio"]').value;
//         const data = ejectBattery(device,slotNum,false);
//         returnData[slotNum] = data;
//     });

//     // Refresh page
//     bindDeviceData(device);

//     return returnData ?? 'Failed to Eject batteries';
// }

// Refresh device data
async function refreshDevice(device,softReload = false){
    const slots = document.querySelectorAll('.slot');
    const loader = document.querySelector('#main-loader');
    const container = document.querySelector('.device-container');
    
    slots.forEach(slot => {
        slot.querySelector("div.battery").style.backgroundColor = "#dadada";
        slot.querySelector("input[type='radio']").setAttribute("disabled","disabled");
        slot.querySelector("input[type='radio']").style.borderColor = "#dadada";    
    });

    if(!softReload){
        container.classList.toggle('d-none');
        loader.classList.toggle('d-none');

        setTimeout(() => {
                loader.classList.toggle('d-none');
                container.classList.toggle('d-none');
        },2000);
    }

    await bindDeviceData(device);

}

// Refresh Device Data Recursivly
function refreshDeviceRecursively(device,ms = 120000){
    setInterval(() => {
        refreshDevice(device,true);
    },ms);
}

function lockBattery(device,slot) {
    return deviceOperation(device,'lock',slot)
}

function unlockBattery(device,slot) {
    return deviceOperation(device,'unlock',slot)
}

async function getSlotDetails(device,slotNum) {
    if (!device || !slotNum) {
        throw new Error('Device ID And slot Number are required');
    }

    const slots = await getSlotsInfo(device);
    if(slots.length > 0){
        slots.forEach((slot) => {
            if(slot.Slot_Num == slotNum){
                $.confirm({
                    type: slot.Erorr_Number != 0 ? 'red' : 'green',
                    theme: 'material',
                    title: `<div class="d-flex justify-content-between gap-3">
                                <span class="d-block">${slot.Slot_Num}</span>
                                <span class="d-block fs-2 fw-bold">${slot.Battery_id}</span>
                            </div>`,
                    content: `<div class="d-flex flex-column">
                            <span class="d-block">${slot.Cabinet_id}</span>
                        </div>`,
                    buttons: {
                        cancel: {
                            text: __('Cancel'),
                            btnClass: 'btn-default',
                        },
                    },
                });
            }
      });
    }
}

// Prepare slot actions 
function prepareSlotActions(device){
    const selectedSlot = document.querySelector('input[type=radio]:checked').value;
    const allActions = document.querySelectorAll(".slot-actions div");

    // Detals Action
    const detailsBtn = document.querySelector(".slot-actions .btn.details");
    detailsBtn.classList.contains('btn-primary') ? '' : detailsBtn.classList.add("btn-primary");
    detailsBtn.addEventListener("click" ,() => { getSlotDetails(device,selectedSlot); });

    // Prohibit Charging Action
    const prohibitBtn = document.querySelector(".slot-actions .btn.prohibit");
    prohibitBtn.classList.contains('btn-danger') ? '' : prohibitBtn.classList.add("btn-danger");
    // callback
    
    // Eject batter action
    const ejectBtn = document.querySelector('.slot-actions .btn.eject');
    ejectBtn.classList.contains('btn-success') ? '' : ejectBtn.classList.add("btn-success");
    detailsBtn.addEventListener("click" ,() => { ejectBattery(device,selectedSlot); });

    // Lock & unlock device
    const lockBtn = document.querySelector('.slot-actions .btn.lock');
    lockBtn.classList.contains('btn-warning') ? '' : lockBtn.classList.add("btn-warning");
    lockBtn.addEventListener("click" ,() => { lockBattery(device,selectedSlot); });
    
}