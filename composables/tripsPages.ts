export const useTripsPages = () => useState("tripsPages", () => []);

export async function getTripsPages() {
  const config = useRuntimeConfig();

  try {
    const response: any = await $fetch(`${config.public.BASE_URL}/trips-pages`, {
      method: "GET",
    });

    const tripsPagesData = useTripsPages();
    tripsPagesData.value = response ?? [];
    
    console.log("GET TRIPS PAGES DATA", response);
    return [response, null];
  } catch (error) {
    console.log("ERROR GETTING TRIPS PAGES DATA", error);
    return [null, error];
  }
}
