import { BlogProps } from '../Blog';
import Button from '@mui/material/Button';
import HomeIcon from '@mui/icons-material/Home';
import IconButton from '@mui/material/IconButton';

export default function AuthButtonsComponent(props: BlogProps) {
    const uri = String(window.location.href);
    return (
        <>
            {props.userService.isLogged() && (
                <>
                    <Button variant={uri.includes('/admin/users') ? 'contained' : 'outlined'} size="small" onClick={() => {
                        window.location.href = '/admin/users';
                    }} >
                        Users
                    </Button>
                    &nbsp;
                    <Button variant={uri.includes('/admin/posts') ? 'contained' : 'outlined'} size="small" onClick={() => {
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
