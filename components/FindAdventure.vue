<template>
  <div>
    <div class=" bg-[url('/perfect-adventure.jpg')] bg-cover bg-center bg-no-repeat">
      <div class="max-w-[1230px] mx-auto py-20">
        <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht"
          style="line-height: 50px;">
          let’s find the perfect <br> adventure for you
        </h2>

        <p class=" mt-5 mb-12 max-w-2xl text-lg text-white text-center mx-auto">
          Find your ideal trek among our diverse selection.
        </p>
      </div>
      <div class="flex items-center bg-[#404857e6] p-3 flex-wrap gap-2 justify-left shadow-lg">
        <!-- Filters Button and Clear All -->
        <div class="flex items-center justify-around space-x-2 text-white w-full sm:w-[15%] min-w-[150px]">
          <span class="flex items-center space-x-1 cursor-pointer">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
          </span>
          <!-- White Divider -->
          <span class="text-white">|</span> <!-- You can also use a border instead -->
          <span class="text-gray-400 cursor-pointer hover:text-white">Clear All</span>
        </div>


        <!-- Search Input -->
        <input type="text" placeholder="Search destinations..."
          class="px-4 py-3 bg-[#000000ab] text-white rounded-md focus:outline-none placeholder-gray-400 w-full sm:w-[30%] min-w-[200px] mx-2 my-1" />

        <!-- Date Range -->
        <div
          class="relative flex items-center justify-between bg-[#afb1b4] rounded-xl overflow-hidden p-2 w-full sm:w-[22%] min-w-[300px] mx-2 my-1">

          <!-- Start Date -->
          <div
            class="relative flex items-center px-3 py-1 bg-white space-x-2 cursor-pointer rounded-xl border border-[#414141]"
            @click="$refs.startDate.showPicker()">
            <i class="fas fa-calendar-alt"></i>
            <span id="startDateLabel" class="text-[#606060]">Start Date</span>
            <input ref="startDate" type="date" class="absolute opacity-0 -top-[5px] left-0 mt-3 pointer-events-none"
              @change="(event) => updateLabel(event, 'startDateLabel')" />

          </div>

          <!-- To Separator -->
          <span class="px-2">To</span>

          <!-- End Date -->
          <div
            class="relative flex items-center px-3 py-1 bg-white space-x-2 cursor-pointer rounded-xl border border-[#414141]"
            @click="$refs.endDate.showPicker()">
            <i class="fas fa-calendar-alt"></i>
            <span id="endDateLabel" class="text-[#606060]">End Date</span>
            <input ref="endDate" type="date" class="absolute opacity-0 -top-[5px] left-0 mt-3 pointer-events-none"
              @change="(event) => updateLabel(event, 'endDateLabel')" />
          </div>
        </div>


        <!-- Budget Dropdown -->
        <select
          class="px-4 py-3 bg-[#afb1b4] rounded-xl focus:outline-none cursor-pointer w-[44%] sm:w-[12%] min-w-[140px] mx-2 my-1">
          <option>Select Budget</option>
          <option>Low</option>
          <option>Medium</option>
          <option>High</option>
        </select>

        <!-- Difficulty Dropdown -->
        <select
          class="px-4 py-3 bg-[#afb1b4] rounded-xl focus:outline-none cursor-pointer w-[44%] sm:w-[12%] min-w-[140px] mx-2 my-1">
          <option>Difficulty Level</option>
          <option>Easy</option>
          <option>Moderate</option>
          <option>Hard</option>
        </select>
      </div>
    </div>

  </div>
</template>

<script setup>
function updateLabel(event, labelId) {
  const label = document.getElementById(labelId);
  const value = event.target.value;
  label.textContent = value ? value : (labelId === 'startDateLabel' ? 'Start Date' : 'End Date');
}
</script>

<style scoped>
/* Custom styles if required */
</style>