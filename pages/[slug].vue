<template>
 <div>
  <PostHeader />
  <img 
    :src="postData?.featuredImage?.node?.sourceUrl" 
    alt="Featured Image" 
    v-if="postData?.featuredImage"
    style="width: 100%; height: 500px; object-fit: cover;"
  />
    <div class="px-16 py-16">
        <h2 class="text-3xl font-bold">{{ postData?.title || "Loading..." }}</h2>
        <p class="text-md mt-4" v-html="postData?.excerpt"></p>
        <div v-html="postData?.content" class="mt-4"></div>
    </div>
  <PageFooter />
</div>

</template>   

<script setup lang="js">
import { useRoute } from 'vue-router';

const route = useRoute();
const config = useRuntimeConfig();

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
</script>
