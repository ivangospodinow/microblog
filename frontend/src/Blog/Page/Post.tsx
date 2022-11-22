import { useEffect, useState } from "react";
import Grid from '@mui/material/Grid';
import Sidebar from '../Sidebar';
import { BlogProps } from '../Blog';
import { ApiErrors, BlogPost } from "../../Service/DataService";
import { useParams } from "react-router-dom";
import Markdown from "../Markdown";
import moment from 'moment';
import { Skeleton } from '@mui/material';
import Chip from '@mui/material/Chip';
import Paper from '@mui/material/Paper';
import Alert from '@mui/material/Alert';
import ApiErrorsComponent from "../Component/ApiErrorsComponent";
import { imagePath } from "../../Tools/Functions";

export default function Post(props: BlogProps) {

  const [post, setPost] = useState<BlogPost>();
  const [postLoaded, setPostLoaded] = useState(false);
  const [errors, setErrors] = useState<ApiErrors>(undefined);

  let { postId } = useParams<{ postId: string }>();

  useEffect(() => {

    (async () => {
      if (postId) {
        const result = await props.dataService.getPosts({
          filter: {
            postId,
          },
        });
        setPostLoaded(true);
        setPost(result.list ? result.list[0] || undefined : undefined);
        setErrors(result.errors || undefined);
      } else {
        setPostLoaded(true);
        setPost(undefined);
        setErrors(undefined);
      }
    })();

  }, [postLoaded]);


  return (
    <>
      <ApiErrorsComponent errors={errors} />
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


          {!postLoaded && (
            <Skeleton variant="rectangular" width={'100%'} height={600} style={{
              marginBottom: '2rem',
            }} />
          )}

          {postLoaded && undefined === post && (
            <Alert severity="error">Unable to load blog post.</Alert>
          )}

          {postLoaded && post && (
            <div>
              <Paper
                sx={{
                  position: 'relative',
                  backgroundColor: 'grey.800',
                  color: '#fff',
                  mb: 4,
                  backgroundSize: 'cover',
                  backgroundRepeat: 'no-repeat',
                  backgroundPosition: 'center',
                  backgroundImage: `url(${imagePath(post.image)})`,
                }}
              >
                {/* Increase the priority of the hero background image */}
                {<img style={{ display: 'none' }} src={imagePath(post.image)} alt={post.image} />}
                <div style={{ height: '35vh' }}></div>
              </Paper>

              <h1>{post.title}</h1>

              <div>
                <Chip label={moment(post.createdAt).format('DD MMMM YYYY')} variant="outlined" />
                &nbsp;
                <Chip label={post.createdByUser.username} variant="outlined" />
              </div>
              <br />
              <div>
                <Markdown className="markdown" style={{
                  paddingBottom: 0,
                }}>
                  {post.content}
                </Markdown>
              </div>
            </div>
          )
          }
        </Grid>
        <Sidebar {...props} />
      </Grid>
    </>
  );
}



