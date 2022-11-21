import { useEffect, useState } from "react";
import Grid from '@mui/material/Grid';
import Sidebar from '../Sidebar';
import { BlogProps } from '../Blog';
import { BlogPosts } from "../../Service/DataService";
import { HOMEPAGE_LAST_POSTS_COUNT } from "../../config";
import { Button, Divider, Typography } from "@mui/material";
import PostsComponent from "../Component/PostsComponent";
import Link from '@mui/material/Link';
import { useParams } from "react-router-dom";
import Alert from '@mui/material/Alert';

export default function Posts(props: BlogProps) {
  const queryParams: any = new URLSearchParams(window.location.search);
  const filter: any = {};
  if (queryParams.get('archive')) {
    filter['archive'] = queryParams.get('archive');
  }

  const [posts, setPosts] = useState<BlogPosts>([]);
  const [postsLoaded, setPostsLoaded] = useState(false);

  let { page } = useParams<{ page: string }>();
  const currentPage = parseInt(page || '1');

  useEffect(() => {

    (async () => {
      const posts = await props.dataService.getPosts({
        list: {
          limit: HOMEPAGE_LAST_POSTS_COUNT,
          page: currentPage,
        },
        filter,
      });
      setPostsLoaded(true);
      setPosts(posts);
    })();


  }, [props, postsLoaded, page]);

  return (
    <>

      <Grid container spacing={5} sx={{ mt: 3 }} style={{
        marginTop: 0,
      }}>
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
            Microblog All Posts
            <span style={{ float: 'right' }}>
              Page #{currentPage}
            </span>
            {queryParams.get('archive') && (
              <span style={{ float: 'right', marginRight: '1em' }}>Archiive for: {queryParams.get('archive')}</span>
            )}
          </Typography>

          <Divider />

          <PostsComponent posts={posts} postsLoaded={postsLoaded} />

          {postsLoaded && (posts && posts.length >= HOMEPAGE_LAST_POSTS_COUNT) && (
            <>
              <br /><br />
              <Divider />
              <br />


              <Link
                href={'/posts/' + (currentPage + 1) + window.location.search}
              >
                <Button
                  variant="contained"
                >See more
                </Button>
              </Link>
            </>
          )}

          {postsLoaded && Array.isArray(posts) && posts.length === 0 && (
            <Alert severity="info">No posts found for requested parameters.</Alert>
          )}

        </Grid>
        <Sidebar {...props} />

      </Grid>
    </>
  );
}



