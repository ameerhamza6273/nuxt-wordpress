<script setup>
import { ref, onMounted } from 'vue';

const loading = ref(true);
const movies = ref(null);
const dedicatedTeam = ref(null);
const headerFooter = ref(null);
const posts = ref(null);
const heroSlider = ref(null); 
const tripsPages = ref(null);


async function fetchData() {
  loading.value = true;
  
  const [moviesRes, moviesErr] = await getMovies();
  const [dedicatedRes, dedicatedErr] = await getDedicatedTeam();
  const [headerFooterRes, headerFooterErr] = await getHeaderFooter();
  const [postsRes, postsErr] = await getPosts();
  const [heroSliderRes, heroSliderErr] = await getHeroSlider(); // Fetch HeroSlider data
  const [tripsPagesRes, tripsPagesErr] = await getTripsPages();

  if (moviesRes) movies.value = moviesRes;
  if (dedicatedRes) dedicatedTeam.value = dedicatedRes;
  if (headerFooterRes) headerFooter.value = headerFooterRes;
  if (postsRes) posts.value = postsRes;
  if (heroSliderRes) heroSlider.value = heroSliderRes; // Assign HeroSlider data
  if (tripsPagesRes) tripsPages.value = tripsPagesRes;

  if (moviesErr) console.error("Error fetching movies:", moviesErr);
  if (dedicatedErr) console.error("Error fetching dedicated team:", dedicatedErr);
  if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);
  if (postsErr) console.error("Error fetching posts:", postsErr);
  if (heroSliderErr) console.error("Error fetching HeroSlider:", heroSliderErr); // Log HeroSlider errors
  if (tripsPagesErr) console.error("Error fetching trips pages:", tripsPagesErr);

  
  loading.value = false;
}

onMounted(fetchData);
</script>

<template>
  <section>
    <article v-if="loading">
      <SpinLoader />
    </article>
    <article v-else>
      <PageNavbar :headerData="headerFooter" />
      <HeroSection :movies="movies" :heroSlider="heroSlider" :tripData="tripsPages" />
      
      <AboutUs :movies="movies" />
      <LogoSection :movies="movies" />
      <NewsletterSec :movies="movies" />
      <Trips :dedicatedTeam="dedicatedTeam" />
      <ExploreTrip :movies="movies" :tripData="tripsPages" />
      <FindAdventure :movies="movies" :tripData="tripsPages" />
      <Post :movies="movies" :postData="posts" />
      <ReviewsSec :dedicatedTeam="dedicatedTeam" />
      <ContactUs :movies="movies" />
      <PageFooter :footerData="headerFooter" />
    </article>
  </section>
</template>