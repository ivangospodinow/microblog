import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';
import Card from '@mui/material/Card';
import CardActionArea from '@mui/material/CardActionArea';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import { BlogPost } from '../Service/DataService';
import { imagePath, splitStringToLines, stringsArrayToString } from '../Tools/Functions';
import moment from 'moment';

type FeaturedPostProps = {
  post: BlogPost
};

export default function FeaturedPost(props: FeaturedPostProps) {
  const { post } = props;

  return (
    <Grid item xs={12} md={6}>
      <CardActionArea component="a" href={'/post/' + post.id}>
        <Card sx={{ display: 'flex' }}>
          <CardContent sx={{ flex: 1 }}>
            <Typography component="h2" variant="h5" style={{
              height: '3em',
              overflow: 'hidden',
            }}>
              {post.title}
            </Typography>
            <Typography variant="subtitle1" color="text.secondary">
              {moment(post.createdAt).format('DD MMMM YYYY')}
            </Typography>
            <Typography variant="subtitle1" paragraph style={{
              height: '5em',
              overflow: 'hidden',
            }}>
              {stringsArrayToString([
                ...splitStringToLines(post.content).slice(0, 3)
              ])}
            </Typography>
            <Typography variant="subtitle1" color="primary">
              Continue reading...
            </Typography>
          </CardContent>
          <CardMedia
            component="img"
            sx={{ width: 160, display: { xs: 'none', sm: 'block' } }}
            image={imagePath(post.image)}
            alt={post.title}
          />
        </Card>
      </CardActionArea>
    </Grid>
  );
}
