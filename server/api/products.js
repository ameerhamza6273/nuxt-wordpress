// server/api/products.js
export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig()
  const query = getQuery(event)

  try {
    const response = await $fetch.raw(`${config.public.wcApiUrl}/wp-json/wc/v3/products`, {
      query: {
        consumer_key: config.wcConsumerKey,
        consumer_secret: config.wcConsumerSecret,
        page: query.page || 1,
        per_page: 10,
        search: query.search || undefined, // Mela-bela string dynamic match ke liye
        category: query.category || undefined,
        order: query.order || 'desc',
        orderby: query.orderby || undefined
      }
    })

    const totalPagesHeader = response.headers.get('x-wp-totalpages')

    return {
      products: response._data || [],
      totalPages: totalPagesHeader ? parseInt(totalPagesHeader) : 1
    }

  } catch (error) {
    console.error("WooCommerce Bridge Error:", error.message)
    return { products: [], totalPages: 1 }
  }
})