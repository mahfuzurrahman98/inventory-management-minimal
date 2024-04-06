import axios from 'axios';
// import useAuthStore from '../stores/auth';
const BASE_URL = '/api/api';

export default axios.create({
    baseURL: BASE_URL,
});

const axiosPrivate = axios.create({
    baseURL: BASE_URL,
    headers: { 'Content-Type': 'application/json' },
    withCredentials: true,
});

// const authStore = useAuthStore();

const setupAxiosInterceptors = (authStore: any) => {
    // request interceptor
    axiosPrivate.interceptors.request.use(
        (config) => {
            if (!config.headers['Authorization']) {
                config.headers['Authorization'] = `Bearer ${authStore.token}`;
            }
            return config;
        },
        (error) => {
            return Promise.reject(error);
        }
    );

    // response interceptor
    axiosPrivate.interceptors.response.use(
        (response) => {
            return response;
        },
        async (error) => {
            const previousRequest = error.config;
            if (error.response.status === 401 && !previousRequest._retry) {
                previousRequest._retry = true;
                try {
                    const newAccessToken = await authStore.refresh();
                    previousRequest.headers[
                        'Authorization'
                    ] = `Bearer ${newAccessToken}`;
                    return axiosPrivate(previousRequest);
                } catch (err) {
                    console.log(err);
                    await authStore.logout();
                    return Promise.reject(error);
                }
            } else {
                console.log(error);
                return Promise.reject(error);
            }
        }
    );
};

export { axiosPrivate, setupAxiosInterceptors };
