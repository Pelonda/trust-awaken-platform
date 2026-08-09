import {
  Box,
  Button,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import QrCode2Icon from '@mui/icons-material/QrCode2'

import { observer } from 'mobx-react-lite'
import { SectionTab } from 'polotno/side-panel'
import type { StoreType } from 'polotno/model/store'

import QRCode from 'qrcode'

interface Props {
  store: StoreType
}

const PLACEHOLDER_URL =
  'https://verification.invalid/{{credential.verification_token}}'

export const ProtectedQrPanel =
  observer(({ store }: Props) => {

    async function addProtectedQr() {
      const page =
        store.activePage ??
        store.pages[0]

      if (!page) {
        return
      }

      const dataUrl =
        await QRCode.toDataURL(
          PLACEHOLDER_URL,
          {
            width: 512,
            margin: 1,
            errorCorrectionLevel: 'H',
          },
        )

      page.addElement({
        type: 'image',

        x: 100,
        y: 100,

        width: 140,
        height: 140,

        src: dataUrl,

        custom: {
          trustAwakenType:
            'verification-qr',

          protected: true,

          variable:
            '{{verification.qr}}',

          sourceVariable:
            '{{verification.url}}',
        },
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
          Verification QR
        </Typography>

        <Typography
          variant="body2"
          color="text.secondary"
          sx={{ mt: 0.5 }}
        >
          Add a protected verification QR placeholder to the template.
        </Typography>

        <Divider sx={{ my: 2 }} />

        <Stack spacing={2}>
          <Button
            variant="contained"
            startIcon={
              <QrCode2Icon />
            }
            onClick={
              addProtectedQr
            }
          >
            Add Verification QR
          </Button>

          <Typography
            variant="caption"
            color="text.secondary"
          >
            The QR destination is generated automatically for each issued credential.
          </Typography>
        </Stack>
      </Box>
    )
  })

export const ProtectedQrSection = {
  name: 'verification-qr',

  Tab: (props: any) => (
    <SectionTab
      name="QR Code"
      {...props}
    >
      <QrCode2Icon
        sx={{
          fontSize: 22,
        }}
      />
    </SectionTab>
  ),

  Panel: ProtectedQrPanel,
}