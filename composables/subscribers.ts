export async function postSubscriber(email: String) {
	const config = useRuntimeConfig();

	try {
		const response: any = await $fetch(`${config.public.GOOGLE_SHEETS_HOOK_URL}&email=${email}`, {
			method: "GET",
		});

		console.log("Subscriber added successfully", response);
		return [response, null];
	} catch (error) {
		console.log("Error occured when trying to add subscriber", error);
		return [null, error];
	}
}