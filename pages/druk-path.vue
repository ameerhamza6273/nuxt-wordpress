<script setup>

import { ref, onMounted } from 'vue';

const loading = ref(true);
const headerFooter = ref(null);
const tripsPages = ref(null);

async function fetchData() {
  loading.value = true;

  const [headerFooterRes, headerFooterErr] = await getHeaderFooter();
  const [tripsPagesRes, tripsPagesErr] = await getTripsPages();

  if (headerFooterRes) headerFooter.value = headerFooterRes;
  if (tripsPagesRes) tripsPages.value = tripsPagesRes;

  if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);
  if (tripsPagesErr) console.error("Error fetching trips pages:", tripsPagesErr);

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
      <DruckPathSlider :druckPage="tripsPages" />
      <PageFooter :footerData="headerFooter" />
    </article>
  </div>
</template>
