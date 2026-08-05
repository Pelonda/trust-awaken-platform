import {
  Card,
  CardContent,
  Chip,
  Divider,
  List,
  ListItem,
  ListItemText,
  Typography,
} from '@mui/material'

const credentials = [
  'CR-000001',
  'CR-000002',
  'CR-000003',
  'CR-000004',
]

export default function LatestCredentials() {
  return (
    <Card
      elevation={0}
      sx={{
        borderRadius: 4,
        border: '1px solid #e5e7eb',
        height: '100%',
      }}
    >
      <CardContent>

        <Typography
          variant="h6"
          fontWeight={700}
          sx={{ mb: 2 }}
        >
          Latest Credentials
        </Typography>

        <List>

          {credentials.map((credential, index) => (

            <div key={credential}>

              <ListItem>

                <ListItemText
                  primary={credential}
                />

                <Chip
                  label="Issued"
                  color="success"
                  size="small"
                />

              </ListItem>

              {index < credentials.length - 1 && (
                <Divider />
              )}

            </div>

          ))}

        </List>

      </CardContent>
    </Card>
  )
}