export default defineEventHandler(async (event) => {
  const body = await readBody(event);
  const config = useRuntimeConfig();

  const valid =
    body?.username === config.adminUsername &&
    body?.password === config.adminPassword;

  if (!valid) {
    throw createError({ statusCode: 401, statusMessage: "Invalid username or password." });
  }

  return { success: true };
});
