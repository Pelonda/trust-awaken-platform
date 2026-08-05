import {
  Alert,
  Button,
  Paper,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import { useState } from 'react'

import { useVerification } from './hooks'

export default function VerificationPage() {

  const [code, setCode] = useState('')

  const { data, refetch, isFetching } =
    useVerification(code)

  return (

    <Paper
      sx={{
        maxWidth: 700,
        mx: 'auto',
        mt: 8,
        p: 5,
      }}
    >

      <Typography
        variant="h4"
        fontWeight={700}
        sx={{ mb: 3 }}
      >
        Verify Credential
      </Typography>

      <Stack spacing={3}>

        <TextField
          label="Verification Code"
          value={code}
          onChange={(e) =>
            setCode(e.target.value)
          }
        />

        <Button
          variant="contained"
          onClick={() => refetch()}
          disabled={
            isFetching ||
            code.length === 0
          }
        >
          Verify
        </Button>

        {data && (

          <Alert
            severity={
              data.valid
                ? 'success'
                : 'error'
            }
          >

            <Typography
              fontWeight={700}
            >
              {data.valid
                ? 'Credential Verified'
                : 'Credential Invalid'}
            </Typography>

            <Typography>
              Number:
              {' '}
              {data.credential_number}
            </Typography>

            <Typography>
              Type:
              {' '}
              {data.credential_type}
            </Typography>

            <Typography>
              Status:
              {' '}
              {data.status}
            </Typography>

          </Alert>

        )}

      </Stack>

    </Paper>

  )

}