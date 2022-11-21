import * as React from 'react';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import Divider from '@mui/material/Divider';
import { BlogPost, BlogPosts } from '../../Service/DataService';
import { splitStringToLines, stringsArrayToString } from '../../Tools/Functions';
import { Skeleton } from '@mui/material';
import { HOMEPAGE_LAST_POSTS_COUNT } from '../../config';
import Markdown from '../Markdown';
import moment from 'moment';
import Alert from '@mui/material/Alert';

interface MainProps {
    posts: BlogPosts;
    postsLoaded: boolean,
}

export default function PostsComponent(props: MainProps) {
    const { posts, postsLoaded } = props;

    return (
        <>
            {!postsLoaded && (
                <div>
                    {Array(HOMEPAGE_LAST_POSTS_COUNT).fill(0).map(() => {
                        return (
                            <Skeleton variant="rectangular" width={'100%'} height={150} style={{
                                marginBottom: '2rem',
                            }} />
                        );
                    })}
                </div>
            )}

            {postsLoaded && undefined === posts && (
                <Alert severity="error">Unable to load blog posts.</Alert>
            )}

            {postsLoaded && posts && posts.map((post: BlogPost) => (
                <div key={'post_' + post.id}>
                    <Markdown className="markdown" style={{
                        paddingBottom: 0,
                    }}>
                        {stringsArrayToString([
                            moment(post.createdAt).format('DD MMMM YYYY') + ' (' + post.createdByUser.username + ')',
                            '# ' + post.title,
                            ...splitStringToLines(post.content).slice(0, 5)
                        ])}
                    </Markdown>
                    <Typography variant="subtitle1" color="primary" component="a" href={'/post/' + post.id}>
                        Continue reading...
                    </Typography>
                </div>
            ))
            }
        </>
    );
}

