import {
  Card,
  CardContent,
  Divider,
  List,
  ListItem,
  ListItemText,
  Typography,
} from '@mui/material'

const events = [
  'Credential issued',
  'Attendance recorded',
  'Participant enrolled',
  'Session completed',
  'Program published',
]

export default function ActivityTimeline() {
  return (
    <Card
      elevation={0}
      sx={{
        borderRadius: 4,
        border: '1px solid #e5e7eb',
      }}
    >
      <CardContent>

        <Typography
          variant="h6"
          fontWeight={700}
          sx={{ mb: 2 }}
        >
          Activity Timeline
        </Typography>

        <List>

          {events.map((event, index) => (

            <div key={event}>

              <ListItem>

                <ListItemText
                  primary={event}
                  secondary="Today"
                />

              </ListItem>

              {index < events.length - 1 && (
                <Divider />
              )}

            </div>

          ))}

        </List>

      </CardContent>
    </Card>
  )
}