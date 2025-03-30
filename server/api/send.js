import { Resend } from "resend";
export default async function (event) {
  try {
    console.log('event =>', event)
    const config = useRuntimeConfig();
    let apiKey = config.public.RESEND_API_KEY;
    const resend = new Resend(apiKey);
    const body = await readBody(event);
    const emailData = {
      from: body.from,
      to: body.to,
      replyTo: body.replyTo,
      subject: body.subject,
      html: body.htmlContent,
    };
    const response = await resend.emails.send(emailData);
    console.log("Email sent successfully", response);
    return response;
  } catch (error) {
    console.error("Error sending email", error);
  }
}