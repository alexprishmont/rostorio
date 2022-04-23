import axios from 'axios';

export function useShiftRequests() {
    const getRequestsByUserId = async (userId) => {
        let data = [];
        await axios.get(`/shifts/requests/${userId}`).then(response => {
            data = response.data;
        });

        return data;
    };

    return {
        getRequestsByUserId,
    };
}
