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

    // handle radio inputs when offline
    // const slots = document.querySelectorAll("[name='slot']");
    // slots.forEach((slot)=> online !== 1 ? slot.setAttribute("disabled","disabled"): '');
    
    const batteries = deviceData?.batteries; // array
    if(batteries.length > 0){
      batteries.forEach((battery) => {
          const slot = document.querySelector(`div#slot-${battery.slotNum}`);
          slot.querySelector("div.battery").style.backgroundColor = "var(--background-color)";
          slot.querySelector("input[type='radio']").removeAttribute("disabled");
          slot.querySelector("input[type='radio']").style.borderColor = "var(--background-color)";
      });
    }

}
// device Operation request
async function deviceOperation(device,operation,slotNum) {
        if (!device || !operation) {
            throw new Error('Device ID And operation type are required');
        }
        
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
function ejectAllbatteries(device) {
    // catch nonexist of device id
    if (!device) {
        throw new Error('Device ID is required');
    }

    // Eject batteries 
    const batteries = document.querySelectorAll('.slot');
    const returnData = [];
    batteries.forEach(battery => {
        const slotNum = battery.querySelector('input[type="radio"]').value;
        const data = ejectBattery(device,slotNum,false);
        returnData[slotNum] = data;
    });

    // Refresh page
    bindDeviceData(device);

    return returnData ?? 'Failed to Eject batteries';
}

// Refresh device data
async function refreshDevice(device){
    const loader = document.querySelector('#main-loader');
    const container = document.querySelector('.device-container');

    container.classList.toggle('d-none');
    loader.classList.toggle('d-none');

    setTimeout(() => {
            loader.classList.toggle('d-none');
            container.classList.toggle('d-none');
    },2000);

    await bindDeviceData(device);

}

function lockBattery(device) {
    return deviceOperation(device,'lock',document.querySelector('input[type=radio]:checked').value)
}

function unlockBattery(device) {
    return deviceOperation(device,'unlock',document.querySelector('input[type=radio]:checked').value)
}