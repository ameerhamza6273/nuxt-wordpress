export const usePosts = () => useState("posts", () => []);

export async function getPosts(body: Object) {
  const config = useRuntimeConfig();

  try {
    console.log(`${config.public.BASE_URL}/posts`);
    const response: any = await $fetch(`${config.public.BASE_URL}/posts?per_page=100&cb=${Date.now()}`, {
      method: "GET",
      body: body,
    });
    console.log(response, 'this is response');

    const posts = usePosts();
    posts.value = response ?? [];
    console.log("GET POSTS", response);
    return [response, null];
  } catch (error) {
    console.log("ERROR GETTING POSTS", error);
    return [null, error];
  }
}
