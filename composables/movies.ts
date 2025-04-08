export const useMovies = () => useState("movies", () => []);

export async function getMovies(body: Object) {
    const config = useRuntimeConfig();
  
    try {
      const response: any = await $fetch(`${config.public.BASE_URL}/movie?cb=${Date.now()}`, {
        method: "GET",
        body: body,
      });
      
      const movies = useMovies()
      movies.value = response ?? []
      console.log("GET MOVIES", response);
      return [response, null];
    } catch (error) {
      console.log("ERROR GETTING MOVIES", error);
      return [null, error];
    }
  }