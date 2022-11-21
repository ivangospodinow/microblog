import { BlogProps } from '../Blog';
import Button from '@mui/material/Button';

export default function AuthButtonsComponent(props: BlogProps) {

    return (
        <>
            {props.userService.isLogged() && (
                <>
                    <Button variant="outlined" size="small" onClick={() => {
                        window.location.href = '/admin/users';
                    }} >
                        Users
                    </Button>
                    &nbsp;
                    <Button variant="outlined" size="small" onClick={() => {
                        window.location.href = '/admin/posts';
                    }} >
                        Posts
                    </Button>
                    &nbsp;
                    <Button variant="outlined" size="small" onClick={() => {
                        props.userService.logout();
                        window.location.href = '/';
                    }} >
                        Logout
                    </Button>
                </>

            )}

            {!props.userService.isLogged() && (
                <Button variant="outlined" size="small" onClick={() => {
                    window.location.href = '/login';
                }} >
                    Log in
                </Button>
            )}
        </>
    );
}
