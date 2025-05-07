<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'

// State
const loading = ref(true)
const movies = ref(null)
const dedicatedTeam = ref(null)
const headerFooter = ref(null)
const posts = ref(null)
const tripsPages = ref(null)
const teamMembers = ref(null)

const route = useRoute()

// Fetch Data
async function fetchData() {
  loading.value = true

  const [moviesRes, moviesErr] = await getMovies()
  const [dedicatedRes, dedicatedErr] = await getDedicatedTeam()
  const [headerFooterRes, headerFooterErr] = await getHeaderFooter()
  const [postsRes, postsErr] = await getPosts()
  const [tripsPagesRes, tripsPagesErr] = await getTripsPages()
  const [teamMembersRes, teamMembersErr] = await getTeamMembers()

  if (moviesRes) movies.value = moviesRes
  if (dedicatedRes) dedicatedTeam.value = dedicatedRes
  if (headerFooterRes) headerFooter.value = headerFooterRes
  if (postsRes) posts.value = postsRes
  if (tripsPagesRes) tripsPages.value = tripsPagesRes
  if (teamMembersRes) teamMembers.value = teamMembersRes

  if (moviesErr) console.error('Error fetching movies:', moviesErr)
  if (dedicatedErr) console.error('Error fetching dedicated team:', dedicatedErr)
  if (headerFooterErr) console.error('Error fetching header & footer:', headerFooterErr)
  if (postsErr) console.error('Error fetching posts:', postsErr)
  if (tripsPagesErr) console.error('Error fetching trips pages:', tripsPagesErr)
  if (teamMembersErr) console.error('Error fetching team members:', teamMembersErr)

  loading.value = false
}

onMounted(fetchData)

// ✅ Scroll to hash once loading is complete
watch(
  () => loading.value,
  async (val) => {
    if (!val && route.hash) {
      await nextTick()
      setTimeout(() => {
        const el = document.querySelector(route.hash)
        if (el) {
          el.scrollIntoView({ behavior: 'smooth' })
        }
      }, 300) // adjust if needed
    }
  }
)
</script>


<template>
  <section>
    <article v-if="loading">
      <SpinLoader />
    </article>
    <article v-else>
      <PageNavbar :headerData="headerFooter" />
      <HeroSection :movies="movies"  :tripData="tripsPages" />
      
      <AboutUs :movies="movies" />
      <LogoSection :movies="movies" />
      <NewsletterSec :movies="movies" />
      <Trips :dedicatedTeam="movies" :teamMembers="teamMembers" />
      <ExploreTrip :movies="movies" :tripData="tripsPages" />
      <FindAdventure :movies="movies" :tripData="tripsPages" />
      <Post :movies="movies" :postData="posts" />
      <ReviewsSec :dedicatedTeam="dedicatedTeam" />
      <ContactUs :movies="movies" />
      <PageFooter :footerData="headerFooter" />
    </article>
  </section>
</template>