export async function sendEmail(body) {
    try {
      console.log("body", body);
      const response = await $fetch("/api/send", {
        method: "POST",
        headers: {
          'Content-Type': 'application/json',
        },
        body: body,
      });
      return [response, null];
    } catch (error) {
      console.error("Error sending email:", error);
      return [null, error];
    }
  }