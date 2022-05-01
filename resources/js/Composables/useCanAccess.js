import {usePage} from '@inertiajs/inertia-vue3';

export function useCanAccess() {
    const canAccess = (roles) => {
        const pageProps = usePage().props.value;
        const userRoles = pageProps.auth.user.roles.map(role => JSON.parse(role.properties).role_code);

        return userRoles.every(role => roles.includes(role));
    };

    return {
        canAccess,
    };
}
