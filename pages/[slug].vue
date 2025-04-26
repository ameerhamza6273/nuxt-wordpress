<template>
  <div>
    <article v-if="loading">
      <SpinLoader />
    </article>
    <div v-else>
      <PageNavbar :headerData="headerFooter" />
      <NuxtImg
      v-if="postData?.featuredImage"
      :src="postData.featuredImage.node.sourceUrl"
      alt="Featured Image"
      width="1000"
      height="600"
      class="w-full object-cover h-[300px] sm:h-[85vh]"
      />

      <div class="px-6 md:px-16 py-16 post-data">
        <h1 class="text-3xl font-bold">{{ postData?.title || "Loading..." }}</h1>
        <!-- <p class="text-md mt-4" v-html="postData?.excerpt"></p> -->
        <!-- <div v-html="postData?.content" class="mt-4"></div> -->
        <div v-html="postContentHTML" class="mt-4"></div>

      </div>
      <PageFooter :footerData="headerFooter" />
    </div>
  </div>

</template>

<script setup lang="js">
import { useRoute } from 'vue-router';
import { ref, onMounted, computed } from 'vue';
import { marked } from 'marked';

const route = useRoute();
const config = useRuntimeConfig();

const loading = ref(true);
const headerFooter = ref(null);

async function fetchData() {
  loading.value = true;

  const [headerFooterRes, headerFooterErr] = await getHeaderFooter();

  if (headerFooterRes) headerFooter.value = headerFooterRes;

  if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);

  loading.value = false;
}

onMounted(fetchData);

const { data: postData, pending, error } = await useFetch(config.public.wordpressUrl, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    query: `
      query GetPostBySlug($slug: String!) {
        postBy(uri: $slug) {
          title
          excerpt
          content
          featuredImage {
            node {
              sourceUrl
            }
          }
        }
      }
    `,
    variables: {
      slug: route.params.slug,
    },
  }),
  transform: (data) => data.data.postBy,
});

if (error.value) {
  console.error("Error fetching post data:", error.value);
}

// ✅ Computed property to convert Markdown to HTML
const postContentHTML = computed(() => {
  return postData.value?.content ? marked(postData.value.content) : '';
});
</script>

<style scoped>
/* Headings */
.post-data ::v-deep(h1),
.post-data ::v-deep(h2),
.post-data ::v-deep(h3),
.post-data ::v-deep(h4),
.post-data ::v-deep(h5),
.post-data ::v-deep(h6) {
  margin-top: 1rem;
  margin-bottom: 0.5rem;
  font-weight: bold;
}
.post-data ::v-deep(h1) {
  font-size: 2em;
}
.post-data ::v-deep(h2) {
  font-size: 1.5em;
}
.post-data ::v-deep(h3) {
  font-size: 1.17em;
}
.post-data ::v-deep(h4) {
  font-size: 1em;
}
.post-data ::v-deep(h5) {
  font-size: 0.83em;
}
.post-data ::v-deep(h6) {
  font-size: 0.67em;
}

/* Links */
.post-data ::v-deep(a) {
  color: #3b82f6;
  text-decoration: underline;
}

/* Lists */
.post-data ::v-deep(ul),
.post-data ::v-deep(ol) {
  margin-left: 1.5rem;
  list-style-type: disc;
}
.post-data ::v-deep(li) {
  margin-bottom: 0.5rem;
}
/* Gallery */
.post-data ::v-deep .wp-block-gallery {
  display: flex;
  flex-wrap: wrap;
  gap: 15px; /* agar thoda gap chahte ho images ke beech */
  margin: 20px 0;
}

.post-data ::v-deep .wp-block-gallery > figure {
  flex: 1 1 100%;
}

.post-data ::v-deep figure > a  {
  max-width: 700px !important; /* Set max width */
  margin: 20px auto !important; /* Center the element and add top and bottom margins */
  display: block !important; /* Ensure the figure behaves as a block element */
}


@media (min-width: 768px) {
  .post-data ::v-deep .wp-block-gallery > figure {
    flex: 1 1 50%; /* 2 images per row on medium screens */
  }
}

@media (min-width: 1024px) {
  .post-data ::v-deep .wp-block-gallery > figure {
    flex: 1 1 25%; /* 4 images per row on large screens */
  }
}


/* Tables */
.post-data ::v-deep(table) {
  width: 100%;
  border-collapse: collapse;
  margin-top: 2rem;
  margin-bottom: 2rem;
}
.post-data ::v-deep(th),
.post-data ::v-deep(td) {
  border: 1px solid #d1d5db; /* light gray border */
  padding: 0.75rem 1rem;
  text-align: left;
}
.post-data ::v-deep(th) {
  background-color: #f3f4f6; /* light background for header */
  font-weight: bold;
}
</style>
