export default {
  async fetch(request, env, ctx) {

    // Obtener URL info
    const url = new URL(request.url);
    const path = url.pathname;

    // Solo rastreamos /c/CODIGO
    if (!path.startsWith("/c/")) {
      return new Response("Not Found", { status: 404 });
    }

    // Extraer código de afiliado
    const referralCode = path.replace("/c/", "").trim();

    // Datos extra UTM + producto
    const productId = url.searchParams.get("product") || null;
    const utm_source = url.searchParams.get("utm_source") || null;
    const utm_medium = url.searchParams.get("utm_medium") || null;
    const utm_campaign = url.searchParams.get("utm_campaign") || null;

    // Info del usuario
    const ip = request.headers.get("CF-Connecting-IP") || "0.0.0.0";
    const ua = request.headers.get("User-Agent") || "Unknown";

    const referrer = request.headers.get("Referer") || null;

    const country = request.cf?.country || null;
    const city = request.cf?.city || null;

    // Fingerprint super simple para detección de abuso
    const fingerprint = await hash(`${ip}-${ua}`);


    // Payload para Laravel API
    const payload = {
      ref: referralCode,
      product_id: productId,
      referrer,
      ip,
      ua,
      fingerprint,
      utm_source,
      utm_medium,
      utm_campaign,
      country,
      city,
      landing: url.toString(),
      secret: env.SECRET_KEY
    };

    // Enviar click a tu backend Laravel
    let resp;

    try {
      resp = await fetch(env.LARAVEL_API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });
    } catch (e) {
      console.error("Error enviando a Laravel:", e);
    }

    // Definir URL final donde se redirige el comprador
    let redirectUrl = url.searchParams.get("redirect");

    if (!redirectUrl) {
      redirectUrl = "https://sitiando.com/"; // fallback global
    }

    // Redirigir al comprador
    return Response.redirect(redirectUrl, 302);
  },
};


// Hash rápido (SHA-256)
async function hash(str) {
  const data = new TextEncoder().encode(str);
  const digest = await crypto.subtle.digest("SHA-256", data);
  return [...new Uint8Array(digest)].map(x => x.toString(16).padStart(2, "0")).join("");
}
