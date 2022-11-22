import CssBaseline from '@mui/material/CssBaseline';
import Container from '@mui/material/Container';
import { createTheme, ThemeProvider } from '@mui/material/styles';
import Header from './Header';
import Footer from './Footer';

import {
  createBrowserRouter,
  RouterProvider,
  Route,
} from "react-router-dom";

import Home from './Page/Home';
import DataService from '../Service/DataService';
import Posts from './Page/Posts';
import Post from './Page/Post';
import Login from './Page/Login';
import UserService from '../Service/UserService';
import Users from './Page/AdminUsers';
import AdminPosts from './Page/AdminPosts';
import AdminUsers from './Page/AdminUsers';


const theme = createTheme();

export type BlogProps = {
  dataService: DataService,
  userService: UserService,
};

export default function Blog(props: BlogProps) {

  const routes = [
    {
      path: "/",
      element: <Home {...props} />,
    },
    {
      path: "/posts/:page",
      element: <Posts {...props} />,
    },
    {
      path: "/post/:postId",
      element: <Post {...props} />,
    },
    {
      path: "/login",
      element: <Login {...props} />,
    },
  ];

  if (props.userService.isLogged()) {
    routes.push(...[
      {
        path: "/admin/users",
        element: <AdminUsers {...props} />,
      },
      {
        path: "/admin/posts",
        element: <AdminPosts {...props} />,
      },
    ]);
  }

  const router = createBrowserRouter(routes);


  return (
    <ThemeProvider theme={theme}>
      <CssBaseline />
      <Container maxWidth="lg">
        <Header {...props} />

        <main style={{
          paddingBottom: '20px',
        }}>
          <RouterProvider router={router} />
        </main>

      </Container>
      <Footer
        title="Footer"
        description="Something here to give the footer a purpose!"
      />
    </ThemeProvider>
  );
}
