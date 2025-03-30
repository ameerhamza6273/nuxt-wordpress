export const usePosts = () => useState("posts", () => []);

export async function getPosts(body: Object) {
  const config = useRuntimeConfig();

  try {
    const response: any = await $fetch(`${config.public.BASE_URL}/posts`, {
      method: "GET",
      body: body,
    });

    const posts = usePosts();
    posts.value = response ?? [];
    console.log("GET POSTS", response);
    return [response, null];
  } catch (error) {
    console.log("ERROR GETTING POSTS", error);
    return [null, error];
  }
}
