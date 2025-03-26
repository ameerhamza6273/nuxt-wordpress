<template>
  <div class="bg-black">
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
      <!-- filter section -->
      <div class="hidden  md:flex items-center bg-[#404857e6] p-3 flex-wrap gap-2 justify-left shadow-lg">
        <!-- Filters and Clear All -->
        <div class="flex items-center justify-around space-x-2 text-white w-full sm:w-[15%] min-w-[150px]">
          <span class="flex items-center space-x-1 cursor-pointer">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
          </span>
          <span class="text-white">|</span>
          <span class="text-gray-400 cursor-pointer hover:text-white">Clear All</span>
        </div>

        <!-- Search Input -->
        <input type="text" placeholder="Search destinations..."
          class="px-4 py-3 bg-[#000000ab] text-white rounded-md focus:outline-none placeholder-gray-400 w-full sm:w-[28%] min-w-[200px] mx-2 my-1" />

        <!-- Date Range -->
        <div
          class="relative flex items-center justify-between bg-[#afb1b4] rounded-xl overflow-hidden p-2 w-full sm:w-[24%] min-w-[300px] mx-2 my-1">
          <!-- Start Date -->
          <div
            class="relative flex items-center px-3 py-1 bg-white space-x-2 cursor-pointer rounded-xl border border-[#414141]"
            @click="triggerPicker($refs.startDateInput)">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ startDateLabel }}</span>
            <input ref="startDateInput" type="date" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
              @focus="(event) => event.target.showPicker && event.target.showPicker()"
              @input="(event) => updateLabel(event, startDateLabel, 'Start Date')" />
          </div>

          <!-- To Separator -->
          <span class="px-2">To</span>

          <!-- End Date -->
          <div
            class="relative flex items-center px-3 py-1 bg-white space-x-2 cursor-pointer rounded-xl border border-[#414141]"
            @click="triggerPicker($refs.endDateInput)">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ endDateLabel }}</span>
            <input ref="endDateInput" type="date" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
              @focus="(event) => event.target.showPicker && event.target.showPicker()"
              @input="(event) => updateLabel(event, endDateLabel, 'End Date')" />
          </div>
        </div>
        <div class="relative w-[44%] sm:w-[12%] min-w-[170px] mx-2 my-1">
          <button @click="toggleDropdown('dropdown1')"
            class="inline-flex w-full justify-between items-center bg-[#afb1b4] px-3 py-3 rounded-md shadow text-gray-900">
            {{ selectedBudget || 'Select Budget' }}
            <svg class="-mr-1 size-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <ul v-if="dropdown1"
            class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
            <li @click="selectBudget('Low')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Low</li>
            <li @click="selectBudget('Medium')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Medium
            </li>
            <li @click="selectBudget('High')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">High</li>
          </ul>
        </div>

        <!-- Dropdown 2: Difficulty Level -->
        <div class="w-[44%] sm:w-[12%] min-w-[170px] mx-2 my-1 relative">
          <button @click="toggleDropdown('dropdown2')"
            class="inline-flex w-full justify-between items-center bg-[#afb1b4] px-3 py-3 rounded-md shadow text-gray-900">
            {{ selectedDifficulty || 'Select Difficulty' }}
            <svg class="-mr-1 size-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <ul v-if="dropdown2"
            class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
            <li @click="selectDifficulty('Easy')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Easy
            </li>
            <li @click="selectDifficulty('Moderate')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              Moderate</li>
            <li @click="selectDifficulty('Hard')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Hard
            </li>
          </ul>
        </div>

      </div>
      <div class="md:hidden flex justify-center bg-[#404857e6] p-3 px-5">
        <button @click="showModal = true"
          class="bg-[#afb1b4] rounded-xl border border-[#414141]  w-full py-3 text-lg font-sebibold rounded-md shadow-lg">
          <i class="fas fa-search mr-3"></i> Start Your Search
        </button>
      </div>
      <transition name="fade">
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-[0.9] flex justify-center items-center z-50">
          <div class="bg-[#404857e6] rounded-lg p-6 w-[90%] max-w-md relative">
            <button @click="showModal = false" class="absolute top-2 right-2 text-white">X</button>
            <span class="flex items-center gap-4 text-white text-xl mb-4 cursor-pointer">
              <i class="fas fa-filter"></i>
              <span>Filters</span>
            </span>

            <!-- Search Input -->
            <input type="text" placeholder="Search destinations..."
              class="px-4 py-3 bg-[#000000ab] text-white rounded-md focus:outline-none placeholder-gray-400 w-full" />


            <!-- Date Range -->
            <div
              class="relative flex items-center justify-between bg-[#afb1b4] rounded-xl overflow-hidden p-2 w-full mt-4">
              <!-- Start Date -->
              <div
                class="relative flex items-center px-3 py-1 bg-white space-x-2 cursor-pointer rounded-xl border border-[#414141]"
                @click="triggerPicker($refs.startDateInput)">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ startDateLabel }}</span>
                <input ref="startDateInput" type="date" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                  @focus="(event) => event.target.showPicker && event.target.showPicker()"
                  @input="(event) => updateLabel(event, startDateLabel, 'Start Date')" />
              </div>

              <!-- To Separator -->
              <span class="px-2">To</span>

              <!-- End Date -->
              <div
                class="relative flex items-center px-3 py-1 bg-white space-x-2 cursor-pointer rounded-xl border border-[#414141]"
                @click="triggerPicker($refs.endDateInput)">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ endDateLabel }}</span>
                <input ref="endDateInput" type="date" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                  @focus="(event) => event.target.showPicker && event.target.showPicker()"
                  @input="(event) => updateLabel(event, endDateLabel, 'End Date')" />
              </div>
            </div>



            <div class="flex justify-between mt-4">
              <div class="w-[48%] relative">
                <button @click="toggleDropdown('dropdown1')"
                  class="inline-flex w-full justify-between items-center bg-[#afb1b4] px-3 py-2 rounded-md shadow text-gray-900">
                  {{ selectedBudget || 'Select Budget' }}
                  <svg class="-mr-1 size-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
                <ul v-if="dropdown1"
                  class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
                  <li @click="selectBudget('Low')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Low
                  </li>
                  <li @click="selectBudget('Medium')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Medium
                  </li>
                  <li @click="selectBudget('High')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    High</li>
                </ul>
              </div>

              <!-- Dropdown 2 -->
              <div class="w-[48%] relative">
                <button @click="toggleDropdown('dropdown2')"
                  class="inline-flex w-full justify-between items-center bg-[#afb1b4] px-3 py-2 rounded-md shadow text-gray-900">
                  {{ selectedDifficulty || 'Select Difficulty' }}
                  <svg class="-mr-1 size-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
                <ul v-if="dropdown2"
                  class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
                  <li @click="selectDifficulty('Easy')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Easy
                  </li>
                  <li @click="selectDifficulty('Moderate')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Moderate</li>
                  <li @click="selectDifficulty('Hard')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Hard
                  </li>
                </ul>
              </div>

            </div>


            <button @click="applyFilters"
              class="bg-[#ffffff1f] py-2  delay-300 text-white rounded-xl text-lg mt-4 shadow-md w-full">
              Apply Filters
            </button>
          </div>
        </div>
      </transition>
    </div>
    <!-- slider section -->
    <div class="max-w-[1290px] mx-auto px-4 sm:px-10 py-10">
      <!-- Swiper Slider -->
      <swiper :modules="[Navigation, Pagination, Grid]" :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }"
        :pagination="{ clickable: true }" :spaceBetween="20" :loop="true" class="w-full relative"
        style="padding-bottom: 70px !important;" :breakpoints="{
          768: {
            slidesPerView: 3,
            grid: {
              rows: 2,
              fill: 'row'
            }
          },
          1024: {
            slidesPerView: 3,
            grid: {
              rows: 2,
              fill: 'row'
            }
          }
        }">
        <!-- Navigation Arrows -->
        <section class="parallax-slider-navigation cursor-pointer">
          <article class="nav-indicator prevArrow">
            <img src="/public/left-slide-icon.svg" alt="Left Arrow"
              class="w-10 absolute z-40 bottom-0 left-[10%] md:left-[30%] lg:left-[37%]" />
          </article>
          <article class="nav-indicator nextArrow">
            <img src="/public/right-slide-icon.svg" alt="Right Arrow"
              class="w-10 absolute z-40 bottom-0 right-[10%] md:right-[30%] lg:right-[37%]" />
          </article>
        </section>
        <!-- Swiper Slides -->
        <swiper-slide v-for="(post, index) in pagesLinks" :key="index">
          <div class="rounded-lg mx-1 mt-6 min-h-[450px] overflow-hidden">
            <!-- Featured Image -->
            <img :src="post.image || '/public/trip1.jpg'" alt="Featured Image" class="rounded-t-lg w-full" />

            <div class="p-4 px-0">
              <!-- Title and Budget -->
              <div class="flex items-center justify-between">
                <h3 class="font-semibold text-xl text-white w-[60%]">{{ post.title }}</h3>
                <p class="w-[40%] text-[#A5A5A5] text-right">{{ post.budjet }}</p>
              </div>

              <!-- Description -->
              <p class="text-[14px] text-[#A5A5A5] my-2 max-h-[130px] overflow-hidden">{{ post.description }}</p>
            </div>
          </div>
        </swiper-slide>
      </swiper>
    </div>

  </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import "swiper/css/grid"; // Add this
import { Navigation, Pagination, Grid } from "swiper/modules"; // Include Grid
import { ref } from "vue";



const pagesLinks = ref([
  { image: "/Destination 1.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 2.png", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 3.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 4.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 5.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 5.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 4.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 5.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },
  { image: "/Destination 5.jpg", title: "Mt. Fuji mountain climb", description: "Duration - ?? days", budjet: "Budget: $$$", },

]);


const startDateLabel = ref('Start Date');
const endDateLabel = ref('End Date');
const showModal = ref(false);

const dropdown1 = ref(false);
const dropdown2 = ref(false);
const selectedBudget = ref('');
const selectedDifficulty = ref('');

const toggleDropdown = (dropdown) => {
  if (dropdown === 'dropdown1') {
    dropdown1.value = !dropdown1.value;
    dropdown2.value = false;
  } else {
    dropdown2.value = !dropdown2.value;
    dropdown1.value = false;
  }
};

const selectBudget = (budget) => {
  selectedBudget.value = budget;
  dropdown1.value = false;
};

const selectDifficulty = (difficulty) => {
  selectedDifficulty.value = difficulty;
  dropdown2.value = false;
};
const applyFilters = () => {
  showModal.value = false;
  // Filters apply logic here
};
const updateLabel = (event, labelRef, defaultText) => {
  labelRef.value = event.target.value || defaultText;
};

// Force click for iOS devices
const triggerPicker = (inputRef) => {
  inputRef.focus();
  inputRef.click();
};
</script>

<style scoped>
:deep(.swiper-pagination-bullet) {
  background-color: #fff !important;
  margin-right: 8px !important;
}

:deep(.swiper-pagination-bullet-active) {
  background-color: transparent !important;
  border: 2px solid #fff;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>