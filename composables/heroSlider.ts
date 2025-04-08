export const useHeroSlider = () => useState("heroSlider", () => []);

export async function getHeroSlider(body: Object) {
    const config = useRuntimeConfig();
  
    try {
      const response: any = await $fetch(`${config.public.BASE_URL}/hero-slider?cb=${Date.now()}`, {
        method: "GET",
        body: body,
      });
      
      const heroSlider = useHeroSlider()
      heroSlider.value = response ?? []
      console.log("GET heroSlider", response);
      return [response, null];
    } catch (error) {
      console.log("ERROR GETTING heroSlider", error);
      return [null, error];
    }
  }