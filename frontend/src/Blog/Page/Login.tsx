import React, { useState } from "react";
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import CssBaseline from '@mui/material/CssBaseline';
import TextField from '@mui/material/TextField';
import Box from '@mui/material/Box';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import Typography from '@mui/material/Typography';
import Container from '@mui/material/Container';
import { BlogProps } from '../Blog';
import { UserLogin } from "../../Service/DataService";
import ApiErrorsComponent from "../Component/ApiErrorsComponent";

export default function Login(props: BlogProps) {

  const [loading, setLoading] = useState<boolean>(false);
  const [loginResult, setLoginResult] = useState<UserLogin>({});

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    setLoading(true);
    const result = await props.dataService.login({
      username: data.get('username'),
      password: data.get('password'),
    });

    setLoginResult(result);
    setLoading(false);

    if (undefined !== result['user']) {
      props.userService.login(result['user']);
      window.location.href = '/';
    }

  };

  return (
    <Container component="main" maxWidth="xs">
      <CssBaseline />
      <Box
        sx={{
          marginTop: 8,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
        }}
      >
        <Avatar sx={{ m: 1, bgcolor: 'secondary.main' }}>
          <LockOutlinedIcon />
        </Avatar>
        <Typography component="h1" variant="h5" style={{ textAlign: 'center' }}>
          Sign in
          <br />
          <small>use admin/admin for demo account.</small>
        </Typography>


        {undefined !== loginResult['errors'] && (
          <ApiErrorsComponent errors={loginResult['errors']} />
        )}

        <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 1 }}>
          <TextField
            margin="normal"
            required
            fullWidth
            id="username"
            label="Username"
            name="username"
            autoComplete="username"
            autoFocus
          />
          <TextField
            margin="normal"
            required
            fullWidth
            name="password"
            label="Password"
            type="password"
            id="password"
            autoComplete="current-password"
          />

          <Button
            disabled={loading}
            type="submit"
            fullWidth
            variant="contained"
            sx={{ mt: 3, mb: 2 }}
          >
            Sign In
          </Button>
        </Box>
      </Box>
    </Container>
  );
}
