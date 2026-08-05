import {
  Card,
  CardContent,
  Divider,
  List,
  ListItem,
  ListItemText,
  Typography,
} from '@mui/material'

const items = [
  'Credential issued',
  'Participant registered',
  'Attendance recorded',
  'Program created',
  'Session completed',
]

export default function RecentActivity() {
  return (
    <Card
      elevation={0}
      sx={{
        borderRadius: 4,
        border: '1px solid #e5e7eb',
        height: 420,
      }}
    >
      <CardContent>

        <Typography
          variant="h6"
          fontWeight={700}
          sx={{ mb: 2 }}
        >
          Recent Activity
        </Typography>

        <List>

          {items.map((item, index) => (

            <div key={item}>

              <ListItem>

                <ListItemText
                  primary={item}
                />

              </ListItem>

              {index < items.length - 1 && (
                <Divider />
              )}

            </div>

          ))}

        </List>

      </CardContent>
    </Card>
  )
}