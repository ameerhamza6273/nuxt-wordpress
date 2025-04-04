<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import "swiper/css/grid"; // Add this
import { Navigation, Pagination, Grid } from "swiper/modules";

const startDateLabel = ref("Start Date");
const endDateLabel = ref("End Date");
const showModal = ref(false);
const dropdown1 = ref(false);
const dropdown2 = ref(false);
const selectedBudget = ref("");
const selectedDifficulty = ref("");
const searchQuery = ref("");
const debouncedSearchQuery = ref("");
const swiperInstance = ref(null);

const startDate = ref(null);
const endDate = ref(null);

const debounce = (fn, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      fn.apply(this, args);
    }, delay);
  };
};

const toggleDropdown = (dropdown) => {
  if (dropdown === "dropdown1") {
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
  const value = event.target.value;
  if (value) {
    labelRef.value = new Date(value).toLocaleDateString("en-GB");
  } else {
    labelRef.value = defaultText;
  }
};

// Force click for iOS devices
const triggerPicker = (inputRef) => {
  inputRef.focus();
  inputRef.click();
};

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
  postData: {
    type: Array,
    required: true,
  },
});

const movieId = 27283;
const movie = computed(() => props.movies.find((m) => m.id === movieId));
const truncateText = (html, wordLimit) => {
  if (process.client) {
    const div = document.createElement("div");
    div.innerHTML = html;
    const text = div.textContent || div.innerText || "";
    const words = text.split(" ");
    return words.length > wordLimit
      ? words.slice(0, wordLimit).join(" ") + "..."
      : text;
  }
  return "";
};

const onSlideChange = () => {
  if (swiperInstance.value) {
    try {
      swiperInstance.value.update();
    } catch (e) {
      console.warn("Swiper update error:", e);
    }
  }
};

const onSwiper = (swiper) => {
  swiperInstance.value = swiper;
};

const updateDebouncedQuery = debounce((value) => {
  debouncedSearchQuery.value = value;
}, 300);

watch(searchQuery, (newVal) => {
  updateDebouncedQuery(newVal);
  console.log("searchQuery changed to:", newVal);
});

const filteredPosts = computed(() => {
  return props.postData.filter((post) => {
    const titleMatches = debouncedSearchQuery.value
      ? post.acf?.post_title
        ?.toLowerCase()
        .includes(debouncedSearchQuery.value.toLowerCase())
      : true;

    const budgetMatches = selectedBudget.value
      ? post.acf?.budget === selectedBudget.value
      : true;

    const difficultyMatches = selectedDifficulty.value
      ? post.acf?.difficulty === selectedDifficulty.value
      : true;

    // Date filtering logic
    const postDate = new Date(post.date); // Assuming post.date exists
    const start = startDate.value ? new Date(startDate.value) : null;
    const end = endDate.value ? new Date(endDate.value) : null;

    let dateMatches = true;
    if (start && end) {
      dateMatches = postDate >= start && postDate <= end;
    } else if (start) {
      dateMatches = postDate >= start;
    } else if (end) {
      dateMatches = postDate <= end;
    }

    return titleMatches && budgetMatches && difficultyMatches && dateMatches;
  });
});

const clearAllFilters = () => {
  searchQuery.value = "";
  debouncedSearchQuery.value = "";
  selectedBudget.value = "";
  selectedDifficulty.value = "";
  startDate.value = null;
  endDate.value = null;
  startDateLabel.value = "Start Date";
  endDateLabel.value = "End Date";
};

const selectedDate = ref(new Date());
</script>

<template>
  <div class="bg-black">
    <div class="bg-[url('/perfect-adventure.jpg')] bg-cover bg-center bg-no-repeat">
      <div class="max-w-[1230px] mx-auto py-20" v-if="movie">
        <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht"
          style="line-height: 50px">
          {{ movie.acf.title }}
        </h2>

        <p class="mt-5 mb-12 max-w-2xl text-lg text-white text-center mx-auto">
          {{ movie.acf.description }}
        </p>
      </div>
      <!-- filter section -->
      <div class="hidden md:flex items-center bg-[#404857e6] p-3 flex-wrap gap-2 justify-left shadow-lg">
        <!-- Filters and Clear All -->
        <div class="flex items-center justify-around space-x-2 text-white w-full sm:w-[15%] min-w-[150px]">
          <span class="flex items-center space-x-1 cursor-pointer">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
          </span>
          <span class="text-white">|</span>
          <span class="text-gray-400 cursor-pointer hover:text-white" @click="clearAllFilters">Clear All</span>
        </div>

        <!-- Search Input -->
        <input type="text" v-model="searchQuery" placeholder="Search destinations..."
          class="px-4 py-3 bg-[#000000ab] text-white rounded-xl border border-[#000000ab] focus:outline-none placeholder-gray-400 w-full sm:w-[28%] min-w-[200px] mx-2 my-1" />

        <!-- Date Range -->

        <div
          class="relative flex items-center justify-between bg-[#afb1b4] rounded-xl overflow-visible p-2 w-full sm:w-[24%] min-w-[300px] mx-2 my-1">
          <vue-date-picker v-model="startDate" class="w-28 rounded-xl " placeholder="Start Date" close-on-scroll
            auto-apply :enable-time-picker="false" />

          <span class="px-2">To</span>

          <vue-date-picker v-model="endDate" input-class="w-28 rounded-xl " placeholder="End Date" close-on-scroll
            auto-apply :enable-time-picker="false" />
        </div>
        <div class="relative w-[44%] sm:w-[12%] min-w-[170px] mx-2 my-1">
          <button @click="toggleDropdown('dropdown1')"
            class="inline-flex w-full justify-between items-center bg-[#afb1b4] px-3 py-3 rounded-xl shadow text-gray-900">
            {{ selectedBudget || "Select Budget" }}
            <svg class="-mr-1 size-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <ul v-if="dropdown1"
            class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
            <li @click="selectBudget('$')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              Low
            </li>
            <li @click="selectBudget('$$')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              Medium
            </li>
            <li @click="selectBudget('$$$')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              High
            </li>
          </ul>
        </div>

        <!-- Dropdown 2: Difficulty Level -->
        <div class="w-[44%] sm:w-[12%] min-w-[170px] mx-2 my-1 relative">
          <button @click="toggleDropdown('dropdown2')"
            class="inline-flex w-full justify-between items-center bg-[#afb1b4] px-3 py-3 rounded-xl shadow text-gray-900">
            {{ selectedDifficulty || "Select Difficulty" }}
            <svg class="-mr-1 size-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <ul v-if="dropdown2"
            class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
            <li @click="selectDifficulty('Easy')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              Easy
            </li>
            <li @click="selectDifficulty('Moderate')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              Moderate
            </li>
            <li @click="selectDifficulty('Hard')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
              Hard
            </li>
          </ul>
        </div>
      </div>
      <div class="md:hidden flex justify-center bg-[#404857e6] p-3 px-5">
        <button @click="showModal = true"
          class="bg-[#afb1b4] rounded-xl border border-[#414141] w-full py-3 text-lg font-sebibold rounded-md shadow-lg">
          <i class="fas fa-search mr-3"></i> Start Your Search
        </button>
      </div>
      <transition name="fade">
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-[0.9] flex justify-center items-center z-50">
          <div class="bg-[#404857e6] rounded-lg p-6 w-[90%] max-w-md relative">
            <button @click="showModal = false" class="absolute top-2 right-2 text-white">
              X
            </button>
            <span class="flex items-center gap-4 text-white text-xl mb-4 cursor-pointer">
              <i class="fas fa-filter"></i>
              <span>Filters</span>
              <span class="text-white">|</span>
              <span class="text-gray-400 cursor-pointer hover:text-white" @click="clearAllFilters">Clear All</span>
            </span>

            <!-- Search Input -->
            <input type="text" v-model="searchQuery" placeholder="Search destinations..."
              class="px-4 py-2 bg-[#000000ab] text-white rounded-md focus:outline-none placeholder-gray-400 w-full" />

            <!-- Date Range -->
            <div
              class="relative flex items-center justify-between bg-[#000000ab] rounded-md overflow-visible p-1 w-full sm:w-[24%] min-w-[300px] mt-4 ">
              <vue-date-picker v-model="startDate" class="w-28 rounded-xl" placeholder="Start Date" close-on-scroll
                auto-apply :enable-time-picker="false" />

              <span class="px-2 text-white">To</span>

              <vue-date-picker v-model="endDate" input-class="w-28 rounded-xl " placeholder="End Date" close-on-scroll
                auto-apply :enable-time-picker="false" />
            </div>
          
            <div class="flex justify-between mt-4">
              <div class="w-[48%] relative">
                <button @click="toggleDropdown('dropdown1')"
                  class="inline-flex w-full justify-between items-center bg-[#000000ab] px-3 py-2 rounded-md shadow text-gray-400">
                  {{ selectedBudget || "Select Budget" }}
                  <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
                <ul v-if="dropdown1"
                  class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
                  <li @click="selectBudget('$')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Low
                  </li>
                  <li @click="selectBudget('$$')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Medium
                  </li>
                  <li @click="selectBudget('$$$')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    High
                  </li>
                </ul>
              </div>

              <!-- Dropdown 2: Difficulty Level -->
              <div class="w-[48%] relative">
                <button @click="toggleDropdown('dropdown2')"
                  class="inline-flex w-full justify-between items-center bg-[#000000ab] px-3 py-2 rounded-md shadow text-gray-400">
                  {{ selectedDifficulty || "Select Difficulty" }}
                  <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
                <ul v-if="dropdown2"
                  class="absolute right-0 z-10 mt-2 w-[100%] bg-white rounded-md shadow-lg ring-1 ring-black/5">
                  <li @click="selectDifficulty('Easy')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Easy
                  </li>
                  <li @click="selectDifficulty('Moderate')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Moderate
                  </li>
                  <li @click="selectDifficulty('Hard')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Hard
                  </li>
                </ul>
              </div>

            </div>

            <button @click="applyFilters"
              class="bg-[#ffffff1f] py-2 delay-300 text-white rounded-xl text-lg mt-4 shadow-md w-full">
              Apply Filters
            </button>
          </div>
        </div>
      </transition>
    </div>
    <!-- slider section -->
    <div class="max-w-[1290px] mx-auto px-4 sm:px-10 py-10">
      <!-- Swiper Slider -->
      <swiper v-if="filteredPosts.length > 0" :key="'swiper-' + filteredPosts.length"
        :modules="[Navigation, Pagination, Grid]" :navigation="{
          nextEl: '.nextArrow',
          prevEl: '.prevArrow',
          disabledClass: 'swiper-button-disabled',
        }" :pagination="{
          clickable: true,
          dynamicBullets: true,
        }" :space-between="20" :loop="false" class="w-full relative" :style="{ 'padding-bottom': '70px' }"
        :breakpoints="{
          320: {
            slidesPerView: 1,
            grid: { rows: 1 },
          },
          768: {
            slidesPerView: 2,
            grid: { rows: 2, fill: 'row' },
          },
          1024: {
            slidesPerView: 3,
            grid: { rows: 2, fill: 'row' },
          },
        }" @swiper="onSwiper" @slide-change="onSlideChange" :observer="true" :observe-parents="true"
        :resize-observer="true">
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
        <swiper-slide v-for="(post, index) in filteredPosts" :key="post.id">
          <div class="rounded-lg mx-1 mt-6 min-h-[450px] overflow-hidden">
            <NuxtLink :to="{ path: post.slug }">
              <!-- Featured Image -->
              <img :src="post.acf.post_image
                ? post.acf.post_image
                : 'https://www.x-trekkers.com/wp-content/uploads/2018/05/taste-of-lapland-Santa-scaled.jpg'
                " alt="Featured Image" class="rounded-t-lg w-full h-[280px] object-cover" />

              <div class="p-4 px-0">
                <!-- Title and Budget -->
                <div class="flex items-center justify-between">
                  <h3 class="font-semibold text-xl text-white w-[75%]">
                    {{ truncateText(post.title.rendered, 4) }}
                  </h3>
                  <p class="w-[25%] text-[#A5A5A5] text-right">Budget: $$$</p>
                </div>
                <p class="text-[#A5A5A5]">
                  {{
                    new Date(post.date).toLocaleDateString("en-GB", {
                      day: "2-digit",
                      month: "short",
                      year: "numeric",
                    })
                  }}
                </p>

                <!-- Description -->
                <p class="text-[14px] text-[#A5A5A5] my-2 max-h-[130px] overflow-hidden" id="htmlContainer"
                  v-html="post.content.rendered"></p>
              </div>
            </NuxtLink>
          </div>
        </swiper-slide>
      </swiper>
    </div>
  </div>
</template>

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

::v-deep(.dp__input) {
  border-radius: 0.75rem;
  border: 1px solid black;
}

::v-deep(.dp__input::placeholder) {
  color: black !important;
  /* 🔲 placeholder text color */
  opacity: 1;
  /* Make sure it's fully visible */
}

::v-deep(.dp__input_icon) {
  color: black;
}

@media screen and (max-width:700px) {
  ::v-deep(.dp__input) {
    border: none;
    background-color: #00000000;
  }

  ::v-deep(.dp__input::placeholder) {
    color: #9ca3af !important;
  }

  ::v-deep(.dp__input_icon) {
    color: #9ca3af !important;
  }
}
</style>