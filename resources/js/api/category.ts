import { axios } from "./global";

export default {
    getCategories: async () => {
        const response = await axios.post('/categories');
        return response.data;
    }
}