import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import Divider from '@mui/material/Divider';
import { BlogPosts } from '../Service/DataService';
import PostsComponent from './Component/PostsComponent';

interface MainProps {
  posts: BlogPosts;
  title: string;
  postsLoaded: boolean,
}

export default function Main(props: MainProps) {
  const { posts, title, postsLoaded } = props;

  return (
    <Grid
      item
      xs={12}
      md={8}
      sx={{
        '& .markdown': {
          py: 3,
        },
      }}
    >

      <Typography variant="h6" gutterBottom>
        {title}
        <Typography variant="subtitle1" color="primary" style={{ float: 'right' }}>
          <a href={'/posts/1'}>
            See All Posts
          </a>
        </Typography>
      </Typography>

      <Divider />

      <PostsComponent posts={posts} postsLoaded={postsLoaded} />

    </Grid>
  );
}
