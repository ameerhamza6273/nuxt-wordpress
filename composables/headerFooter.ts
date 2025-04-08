export const useHeaderFooter = () => useState("headerFooter", () => []);

export async function getHeaderFooter() {
  const config = useRuntimeConfig();

  try {
    const response: any = await $fetch(`${config.public.BASE_URL}/headerfooter?cb=${Date.now()}`, {
      method: "GET",
    });

    const headerFooterData = useHeaderFooter();
    headerFooterData.value = response ?? [];
    
    console.log("GET HEADER FOOTER DATA", response);
    return [response, null];
  } catch (error) {
    console.log("ERROR GETTING HEADER FOOTER DATA", error);
    return [null, error];
  }
}
