import { ApiError } from "../../Service/DataService";
import Alert from '@mui/material/Alert';

type Props = {
  errors?: ApiError[],
};

export default function ApiErrorsComponent(props: Props) {
  return (
    <>
      {undefined !== props['errors'] && (
        <Alert severity="warning">
          {props['errors'].map((error: ApiError) => {
            return (
              <div>
                {'[' + error.property + '] ' + error.message}
              </div>
            );
          })}
        </Alert>
      )}
    </>
  );
}
