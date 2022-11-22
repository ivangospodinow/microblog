import { useEffect, useState } from "react";
import Grid from '@mui/material/Grid';
import Main from '../Main';
import Sidebar from '../Sidebar';
import { BlogProps } from '../Blog';
import { ApiErrors, BlogPosts } from "../../Service/DataService";
import { HOMEPAGE_LAST_POSTS_COUNT } from "../../config";
import FeaturedPostsComponent from "../Component/FeaturedPostsComponent";
import ApiErrorsComponent from "../Component/ApiErrorsComponent";


export default function Home(props: BlogProps) {

  const [posts, setPosts] = useState<BlogPosts>([]);
  const [postsLoaded, setPostsLoaded] = useState(false);
  const [errors, setErrors] = useState<ApiErrors>(undefined);

  useEffect(() => {

    (async () => {
      const result = await props.dataService.getPosts({
        list: {
          limit: HOMEPAGE_LAST_POSTS_COUNT,
          page: 1,
        }
      });
      setPostsLoaded(true);
      setPosts(result.list);
      setErrors(result.errors || undefined);
    })();


  }, [postsLoaded]);


  const [featuredPosts, setFeaturedPosts] = useState<BlogPosts>([]);
  const [featuredPostsLoaded, setFeaturedPostsLoaded] = useState(false);

  useEffect(() => {

    (async () => {
      const result = await props.dataService.getPosts({
        list: {
          limit: 3,
        },
        filter: {
          featured: true,
        },
      });
      setFeaturedPostsLoaded(true);
      setFeaturedPosts(result.list);
      setErrors(result.errors || undefined);
    })();


  }, [featuredPostsLoaded]);


  return (
    <>
      <ApiErrorsComponent errors={errors} />

      <FeaturedPostsComponent featuredPosts={featuredPosts} featuredPostsLoaded={featuredPostsLoaded} />
      <Grid container spacing={5} sx={{ mt: 3 }}>
        <Main title="Latest from the microblog" posts={posts} postsLoaded={postsLoaded} />
        <Sidebar {...props} />
      </Grid>
    </>
  );
}
