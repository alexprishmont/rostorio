import axios from 'axios';

export function useShifts() {
    const getShift = async (date) => {
        let data = [];
        await axios.get(`/shifts/getByDate/${date}`).then(response => {
            data = response.data;
        });

        return data;
    };

    return {
        getShift,
    };
}
