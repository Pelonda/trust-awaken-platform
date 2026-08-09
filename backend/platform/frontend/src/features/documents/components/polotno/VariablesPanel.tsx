import {
  Box,
  Button,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import { observer } from 'mobx-react-lite'
import { SectionTab } from 'polotno/side-panel'
import type { StoreType } from 'polotno/model/store'

import DataObjectIcon from '@mui/icons-material/DataObject'

interface Props {
  store: StoreType
}

const variables = [
  {
    label: 'Participant Name',
    value: '{{participant.name}}',
  },
  {
    label: 'Participant Email',
    value: '{{participant.email}}',
  },
  {
    label: 'Program Title',
    value: '{{program.title}}',
  },
  {
    label: 'Program Code',
    value: '{{program.code}}',
  },
  {
    label: 'Credential Number',
    value: '{{credential.number}}',
  },
  {
    label: 'Issue Date',
    value: '{{credential.issue_date}}',
  },
  {
    label: 'Expiry Date',
    value: '{{credential.expiry_date}}',
  },
  {
    label: 'Organization Name',
    value: '{{organization.name}}',
  },
  {
    label: 'Trainer Name',
    value: '{{trainer.name}}',
  },
  {
    label: 'Verification URL',
    value: '{{verification.url}}',
  },
]

export const VariablesPanel =
  observer(({ store }: Props) => {

    function addVariable(
      value: string,
    ) {

      const page =
        store.activePage ??
        store.pages[0]

      if (!page) {
        return
      }

      page.addElement({
        type: 'text',

        x: 100,
        y: 100,

        width: 300,

        text: value,

        fontSize: 28,

        fill: '#111827',

        align: 'center',
      })

    }

    return (
      <Box
        sx={{
          p: 2,
          height: '100%',
          overflow: 'auto',
        }}
      >
        <Typography
          variant="h6"
          fontWeight={700}
        >
          Variables
        </Typography>

        <Typography
          variant="body2"
          color="text.secondary"
          sx={{
            mt: 0.5,
          }}
        >
          Insert dynamic credential data.
        </Typography>

        <Divider sx={{ my: 2 }} />

        <Stack spacing={1}>
          {variables.map(
            (variable) => (
              <Button
                key={variable.value}
                variant="outlined"
                fullWidth
                sx={{
                  justifyContent:
                    'flex-start',

                  textTransform:
                    'none',
                }}
                onClick={() =>
                  addVariable(
                    variable.value,
                  )
                }
              >
                {variable.label}
              </Button>
            ),
          )}
        </Stack>
      </Box>
    )

  })

export const VariablesSection = {

  name: 'variables',

  Tab: (props: any) => (
    <SectionTab
      name="Variables"
      {...props}
    >
      <DataObjectIcon
        sx={{
          fontSize: 22,
        }}
      />
    </SectionTab>
  ),

  Panel: VariablesPanel,

}