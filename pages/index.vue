<script setup>
import { ref, onMounted } from 'vue';

const loadingMovies = ref(false);
const loadingDedicated = ref(false);
const loadingHeaderFooter = ref(false);
const loadingPosts = ref(false);

const movies = useMovies();
const dedicatedTeam = useDedicatedTeam();
const headerFooter = useHeaderFooter();
const posts = usePosts();

async function GetMovies() {
  loadingMovies.value = true;
  const [res, err] = await getMovies();
  loadingMovies.value = false;
  if (res) {
    console.log("RES MOVIES", res);
  } else {
    console.error("Error fetching movies:", err);
  }
}

async function GetDedicatedTeam() {
  loadingDedicated.value = true;
  const [res, err] = await getDedicatedTeam();
  loadingDedicated.value = false;
  if (res) {
    console.log("RES DEDICATED TEAM", res);
  } else {
    console.error("Error fetching dedicated team:", err);
  }
}

async function GetHeaderFooter() {
  loadingHeaderFooter.value = true;
  const [res, err] = await getHeaderFooter();
  loadingHeaderFooter.value = false;
  if (res) {
    console.log("RES HEADER FOOTER", res);
  } else {
    console.error("Error fetching header & footer:", err);
  }
}

async function GetPosts() {
  loadingPosts.value = true;
  const [res, err] = await getPosts();
  loadingPosts.value = false;
  if (res) {
    console.log("RES POSTS", res);
  } else {
    console.error("Error fetching posts:", err);
  }
}

onMounted(() => {
  GetMovies();
  GetDedicatedTeam();
  GetHeaderFooter();
  GetPosts();
});
</script>

<template>
  <section>
    <article v-if="loadingMovies || loadingDedicated || loadingHeaderFooter || loadingPosts">
      <SpinLoader />
    </article>

    <article v-else>
      <PageNavbar :headerData="headerFooter" />
      <HeroSection :movies="movies" />
      <AboutUs :movies="movies" />
      <LogoSection :movies="movies" />
      <SignMeSec :movies="movies" />
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
