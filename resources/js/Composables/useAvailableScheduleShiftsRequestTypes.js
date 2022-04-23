import axios from 'axios';

export function useAvailableScheduleShiftsRequestTypes() {
    const getTypes = async () => {
        let result = [];

        await axios.get('/shifts/requests/types').then(response => {
            result = response.data;
        });

        return result;
    };
    return {
        getTypes,
    };
}
