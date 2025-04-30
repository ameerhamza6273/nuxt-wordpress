// composable/useTeamMembers.ts
export const useTeamMembers = () => useState("teamMembers", () => []);

export async function getTeamMembers() {
  const config = useRuntimeConfig();

  try {
    const response: any = await $fetch(`${config.public.BASE_URL}/team-members?cb=${Date.now()}`, {
      method: "GET",
    });

    const teamMembersData = useTeamMembers();
    teamMembersData.value = response ?? [];

    console.log("GET TEAM MEMBERS DATA", response);
    return [response, null];
  } catch (error) {
    console.log("ERROR GETTING TEAM MEMBERS DATA", error);
    return [null, error];
  }
}
