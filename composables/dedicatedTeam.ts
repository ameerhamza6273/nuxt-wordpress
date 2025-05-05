// dedicatedTeam.ts
export const useDedicatedTeam = () => useState("dedicatedTeam", () => []);

export async function getDedicatedTeam() {
    const config = useRuntimeConfig();
  
    try {
      const response: any = await $fetch(`${config.public.BASE_URL}/reviews-appraisals?cb=${Date.now()}`, {
        method: "GET",
      });
      
      const dedicatedTeam = useDedicatedTeam();
      dedicatedTeam.value = response ?? [];
      console.log("GET DEDICATED TEAM", response);
      return [response, null];
    } catch (error) {
      console.log("ERROR GETTING DEDICATED TEAM", error);
      return [null, error];
    }
}
