import Grid from '@mui/material/Grid';
import { BlogPosts } from '../../Service/DataService';
import { Skeleton } from '@mui/material';
import MainFeaturedPost from '../MainFeaturedPost';
import FeaturedPost from '../FeaturedPost';
import Alert from '@mui/material/Alert';

interface MainProps {
    featuredPosts: BlogPosts;
    featuredPostsLoaded: boolean,
}

export default function FeaturedPostsComponent(props: MainProps) {
    const { featuredPosts, featuredPostsLoaded } = props;
    return (
        <>
            {!featuredPostsLoaded && (
                <>
                    <Skeleton variant="rectangular" width={'100%'} height={400} style={{
                        marginBottom: '2rem',
                    }} />
                </>
            )}

            {featuredPostsLoaded && undefined === featuredPosts && (
                <Alert severity="error">Unable to load featured posts.</Alert>
            )}

            {featuredPostsLoaded && featuredPosts && (
                <>
                    {undefined !== featuredPosts[0] && (
                        <MainFeaturedPost post={featuredPosts[0]} />
                    )}
                    <Grid container spacing={4}>
                        {([...featuredPosts.slice(1)]).map((post) => (
                            <FeaturedPost key={post.id} post={post} />
                        ))}
                    </Grid>
                </>
            )}
        </>
    );
}
