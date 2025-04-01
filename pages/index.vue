<script setup>
import { ref, onMounted } from 'vue';

const loading = ref(true);
const movies = ref(null);
const dedicatedTeam = ref(null);
const headerFooter = ref(null);
const posts = ref(null);

async function fetchData() {
  loading.value = true;
  
  const [moviesRes, moviesErr] = await getMovies();
  const [dedicatedRes, dedicatedErr] = await getDedicatedTeam();
  const [headerFooterRes, headerFooterErr] = await getHeaderFooter();
  const [postsRes, postsErr] = await getPosts();
  
  if (moviesRes) movies.value = moviesRes;
  if (dedicatedRes) dedicatedTeam.value = dedicatedRes;
  if (headerFooterRes) headerFooter.value = headerFooterRes;
  if (postsRes) posts.value = postsRes;
  
  if (moviesErr) console.error("Error fetching movies:", moviesErr);
  if (dedicatedErr) console.error("Error fetching dedicated team:", dedicatedErr);
  if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);
  if (postsErr) console.error("Error fetching posts:", postsErr);
  
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
      <HeroSection :movies="movies" />
      <AboutUs :movies="movies" />
      <LogoSection :movies="movies" />
      <NewsletterSec :movies="movies" />
      <Trips :dedicatedTeam="dedicatedTeam" />
      <ExploreTrip :movies="movies" />
      <FindAdventure :movies="movies" />
      <Post :movies="movies" />
      <ReviewsSec :dedicatedTeam="dedicatedTeam" />
      <ContactUs :movies="movies" />
      <PageFooter :footerData="headerFooter" />
    </article>
  </section>
</template>