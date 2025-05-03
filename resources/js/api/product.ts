import { axios } from "./global";

export default {
    getProductsForYou: async () => {
        const response = await axios.post('/products/for-you');
        return response.data;
    },

    getPopularProducts: async () => {
        const response = await axios.post('/products/popular');
        return response.data;
    },

    getProductOfTheDay: async () => {
        const response = await axios.post('/products/of-the-day');
        return response.data;
    },

    getProducts: async (data: any) => {
        const response = await axios.post('/products', data);
        return response.data;
    }
}