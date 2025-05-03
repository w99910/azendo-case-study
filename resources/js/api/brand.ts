import { axios } from "./global";

export default {
    getBrands: async () => {
        const response = await axios.post('/brands');
        return response.data;
    }
}