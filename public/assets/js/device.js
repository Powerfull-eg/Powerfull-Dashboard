// Bind device data for device show page
async function bindDeviceData(device) {
    // uncheck selected slot
    if(document.querySelector('input[type=radio]:checked')) {
        document.querySelector('input[type=radio]:checked').checked = false;
  }

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

  // clear slot old data
  document.querySelectorAll("[id^='slot-']").forEach(slot => {
      slot.removeAttribute("slot-err");
      slot.removeAttribute("locked");
  });

  // Handle Batteries And Slots data
  const slots = await getSlotsInfo(device); // array
  console.log(slots);
  if(slots.length > 0){
      slots.forEach((slot) => {
        const slotElement = document.querySelector(`div#slot-${slot.Slot_Num}`);
        slotElement.querySelector("input[type='radio']").addEventListener("click",() => { 
        prepareSlotActions(device);
        // Replace Unlock and Lock button
            const lockBtn = document.querySelector('.slot-actions .btn.lock');
            const unlockBtn = document.querySelector('.slot-actions .btn.unlock');
            slot.Erorr_Number == 7 ? lockBtn.classList.add('d-none') : lockBtn.classList.remove('d-none');
            slot.Erorr_Number == 7 ? unlockBtn.classList.remove('d-none') : unlockBtn.classList.add('d-none');
        });
        
        // Slot filled
        if(slot.Battery_Exist){
            slotElement.querySelector("div.battery").style.backgroundColor = slot.Erorr_Number != 0 ? "#ff000085" : "var(--background-color)";
            slotElement.querySelector("input[type='radio']").style.borderColor = slot.Erorr_Number != 0 ? "#ff000085" : "var(--background-color)";
            
            // Error Slots 
            if(slot.Erorr_Number != '0'){
                slotElement.setAttribute('slot-err',true);
                slot.Erorr_Number == 7 ? slotElement.setAttribute('locked',true) : '';
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

    deviceOperation(device,"pop",slotNum);

    if(refresh) bindDeviceData(device);
}

// Refresh device data
async function refreshDevice(device,softReload = false){
  const slots = document.querySelectorAll('.slot');
  const loader = document.querySelector('#main-loader');
  const container = document.querySelector('.device-container');
  
  slots.forEach(slot => {
      slot.querySelector("div.battery").style.backgroundColor = "#dadada";
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
          console.log(slot)
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

  const allActions = document.querySelectorAll(".slot-actions div");
  
  // Detals Action
  const detailsBtn = document.querySelector(".slot-actions .btn.details");
  detailsBtn.classList.contains('btn-primary') ? '' : detailsBtn.classList.add("btn-primary");

  // Prohibit Charging Action
  const prohibitBtn = document.querySelector(".slot-actions .btn.prohibit");
  prohibitBtn.classList.contains('btn-danger') ? '' : prohibitBtn.classList.add("btn-danger");
  // callback
  
  // Eject batter action
  const ejectBtn = document.querySelector('.slot-actions .btn.eject');
  ejectBtn.classList.contains('btn-success') ? '' : ejectBtn.classList.add("btn-success");

  // Lock & unlock device
  const lockBtn = document.querySelector('.slot-actions .btn.lock');
  const unlockBtn = document.querySelector('.slot-actions .btn.unlock');
  [lockBtn,unlockBtn].forEach(btn => {
      btn.classList.contains('btn-warning') ? '' : btn.classList.add("btn-warning"); 
  });
  
  allActions.forEach(btn => {
      btn.classList.remove('btn-secondary');
    //   btn.addEventListener('click',() => {
    //       const text = btn.innerHTML;
    //       const loader = "<span class='loader text-light' style='width: 1.5rem;height: 1.5rem'></span>";
    //       btn.innerHTML = loader;
    //       setTimeout(() => {
    //           btn.innerHTML = text;
    //       },8000);
    //   });
  });
}