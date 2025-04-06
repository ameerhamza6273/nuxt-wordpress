<script setup>

import { ref, onMounted } from 'vue';

const loading = ref(true);
const headerFooter = ref(null);
const tripsPages = ref(null);
const posts = ref(null);

async function fetchData() {
  loading.value = true;

  const [headerFooterRes, headerFooterErr] = await getHeaderFooter();
  const [tripsPagesRes, tripsPagesErr] = await getTripsPages();
  const [postsRes, postsErr] = await getPosts();

  if (headerFooterRes) headerFooter.value = headerFooterRes;
  if (tripsPagesRes) tripsPages.value = tripsPagesRes;
  if (postsRes) posts.value = postsRes;

  if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);
  if (tripsPagesErr) console.error("Error fetching trips pages:", tripsPagesErr);
  if (postsErr) console.error("Error fetching posts:", postsErr);

  loading.value = false;
}

onMounted(fetchData);
</script>
<template>
  <div>
    <article v-if="loading">
      <SpinLoader />
    </article>
    <article v-else>
      <PageNavbar :headerData="headerFooter" />
      <MontBlacSlider :montBlancPage="posts" />
      <PageFooter :footerData="headerFooter" />
    </article>
  </div>
</template>
