import {
  Alert,
  Box,
  Button,
  Divider,
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import {
  useEffect,
  useMemo,
  useState,
} from 'react'

import type {
  Canvas,
} from 'fabric'

import {
  DOCUMENT_LANGUAGES,
  DOCUMENT_TYPES,
  defaultPaperForDocumentType,
  documentHasBackSide,
  type DocumentLanguage,
  type DocumentType,
} from '../../config/documentTypes'

import {
  PAPER_SIZES,
  physicalToCanvas,
  resolvePaperSize,
  type PaperOrientation,
  type PaperUnit,
} from '../../config/paperSizes'

export type DocumentSide =
  | 'front'
  | 'back'

export interface DocumentSetupState {
  schemaVersion: 2

  documentType:
    DocumentType

  language:
    DocumentLanguage

  paperSize:
    string

  orientation:
    PaperOrientation

  paperWidth:
    number

  paperHeight:
    number

  paperUnit:
    PaperUnit

  canvasWidth:
    number

  canvasHeight:
    number

  activeSide:
    DocumentSide

  hasBackSide:
    boolean
}

interface Props {
  canvas:
    Canvas | null

  value:
    DocumentSetupState | null

  activeSide:
    DocumentSide

  onSideChange(
    side:
      DocumentSide,
  ): void | Promise<void>

  onChange(
    setup:
      DocumentSetupState,
  ): void
}

export default function FabricDocumentSetup({
  canvas,
  value,
  activeSide,
  onSideChange,
  onChange,
}: Props) {
  const [
    documentType,
    setDocumentType,
  ] =
    useState<DocumentType>(
      'certificate',
    )

useEffect(
  () => {
    if (!value) {
      return
    }

    setDocumentType(
      value.documentType,
    )

    setLanguage(
      value.language,
    )

    setPaperSize(
      value.paperSize,
    )

    setOrientation(
      value.orientation,
    )

    if (
      value.paperSize ===
      'custom'
    ) {
      setCustomWidth(
        value.paperWidth,
      )

      setCustomHeight(
        value.paperHeight,
      )

      setCustomUnit(
        value.paperUnit,
      )
    }
  },
  [
    value,
  ],
)

  const [
    language,
    setLanguage,
  ] =
    useState<DocumentLanguage>(
      'en',
    )

  const [
    paperSize,
    setPaperSize,
  ] =
    useState(
      'a4',
    )

  const [
    orientation,
    setOrientation,
  ] =
    useState<PaperOrientation>(
      'landscape',
    )

  const [
    customWidth,
    setCustomWidth,
  ] =
    useState(
      297,
    )

  const [
    customHeight,
    setCustomHeight,
  ] =
    useState(
      210,
    )

  const [
    customUnit,
    setCustomUnit,
  ] =
    useState<PaperUnit>(
      'mm',
    )

  const hasBackSide =
    documentHasBackSide(
      documentType,
    )

  const paper =
    useMemo(
      () => {
        if (
          paperSize ===
          'custom'
        ) {
          let width =
            customWidth

          let height =
            customHeight

          if (
            orientation ===
              'portrait' &&
            width > height
          ) {
            ;[
              width,
              height,
            ] = [
              height,
              width,
            ]
          }

          if (
            orientation ===
              'landscape' &&
            height > width
          ) {
            ;[
              width,
              height,
            ] = [
              height,
              width,
            ]
          }

          return {
            key:
              'custom',

            label:
              'Custom',

            category:
              'custom',

            width,

            height,

            unit:
              customUnit,

            orientation,
          }
        }

        return resolvePaperSize(
          paperSize,
          orientation,
        )
      },
      [
        paperSize,
        orientation,
        customWidth,
        customHeight,
        customUnit,
      ],
    )

  const canvasSize =
    useMemo(
      () =>
        physicalToCanvas(
          paper.width,
          paper.height,
          paper.unit,
        ),
      [
        paper,
      ],
    )

  async function changeDocumentType(
    value:
      DocumentType,
  ) {
    setDocumentType(
      value,
    )

    const defaults =
      defaultPaperForDocumentType(
        value,
      )

    setPaperSize(
      defaults.paperSize,
    )

    setOrientation(
      defaults.orientation,
    )

    if (
      activeSide !==
      'front'
    ) {
      await onSideChange(
        'front',
      )
    }
  }

  function buildSetup():
    DocumentSetupState {
    return {
      schemaVersion:
        2,

      documentType,

      language,

      paperSize,

      orientation,

      paperWidth:
        paper.width,

      paperHeight:
        paper.height,

      paperUnit:
        paper.unit,

      canvasWidth:
        canvasSize.width,

      canvasHeight:
        canvasSize.height,

      activeSide,

      hasBackSide,
    }
  }

  function apply() {
    if (!canvas) {
      return
    }

    if (
      paper.width <= 0 ||
      paper.height <= 0
    ) {
      return
    }

    canvas.setDimensions({
      width:
        canvasSize.width,

      height:
        canvasSize.height,
    })

    canvas.calcOffset()

    canvas.requestRenderAll()

    onChange?.(
      buildSetup(),
    )
  }

  return (
    <Box>
      <Typography
        variant="subtitle1"
        fontWeight={700}
      >
        Document Setup
      </Typography>

      <Typography
        variant="caption"
        color="text.secondary"
      >
        Type, language and physical
        output format.
      </Typography>

      <Stack
        spacing={2}
        sx={{
          mt:
            2,
        }}
      >
        <FormControl
          size="small"
          fullWidth
        >
          <InputLabel>
            Document Type
          </InputLabel>

          <Select
            label="Document Type"
            value={
              documentType
            }
            onChange={
              event =>
                void changeDocumentType(
                  event.target
                    .value as
                    DocumentType,
                )
            }
          >
            {DOCUMENT_TYPES.map(
              option => (
                <MenuItem
                  key={
                    option.value
                  }
                  value={
                    option.value
                  }
                >
                  {
                    option.label
                  }
                </MenuItem>
              ),
            )}
          </Select>
        </FormControl>

        <FormControl
          size="small"
          fullWidth
        >
          <InputLabel>
            Language
          </InputLabel>

          <Select
            label="Language"
            value={
              language
            }
            onChange={
              event =>
                setLanguage(
                  event.target
                    .value as
                    DocumentLanguage,
                )
            }
          >
            {DOCUMENT_LANGUAGES.map(
              option => (
                <MenuItem
                  key={
                    option.value
                  }
                  value={
                    option.value
                  }
                >
                  {
                    option.label
                  }
                </MenuItem>
              ),
            )}
          </Select>
        </FormControl>

        <FormControl
          size="small"
          fullWidth
        >
          <InputLabel>
            Paper Size
          </InputLabel>

          <Select
            label="Paper Size"
            value={
              paperSize
            }
            onChange={
              event =>
                setPaperSize(
                  event.target
                    .value,
                )
            }
          >
            {Object.values(
              PAPER_SIZES,
            ).map(
              size => (
                <MenuItem
                  key={
                    size.key
                  }
                  value={
                    size.key
                  }
                >
                  {
                    size.label
                  }
                </MenuItem>
              ),
            )}

            <Divider />

            <MenuItem
              value="custom"
            >
              Custom Size
            </MenuItem>
          </Select>
        </FormControl>

        <FormControl
          size="small"
          fullWidth
        >
          <InputLabel>
            Orientation
          </InputLabel>

          <Select
            label="Orientation"
            value={
              orientation
            }
            onChange={
              event =>
                setOrientation(
                  event.target
                    .value as
                    PaperOrientation,
                )
            }
          >
            <MenuItem
              value="portrait"
            >
              Portrait
            </MenuItem>

            <MenuItem
              value="landscape"
            >
              Landscape
            </MenuItem>
          </Select>
        </FormControl>

        {paperSize ===
          'custom' && (
          <>
            <Stack
              direction="row"
              spacing={1}
            >
              <TextField
                size="small"
                label="Width"
                type="number"
                value={
                  customWidth
                }
                onChange={
                  event =>
                    setCustomWidth(
                      Number(
                        event.target
                          .value,
                      ),
                    )
                }
              />

              <TextField
                size="small"
                label="Height"
                type="number"
                value={
                  customHeight
                }
                onChange={
                  event =>
                    setCustomHeight(
                      Number(
                        event.target
                          .value,
                      ),
                    )
                }
              />
            </Stack>

            <FormControl
              size="small"
              fullWidth
            >
              <InputLabel>
                Unit
              </InputLabel>

              <Select
                label="Unit"
                value={
                  customUnit
                }
                onChange={
                  event =>
                    setCustomUnit(
                      event.target
                        .value as
                        PaperUnit,
                    )
                }
              >
                <MenuItem
                  value="mm"
                >
                  Millimeters
                </MenuItem>

                <MenuItem
                  value="cm"
                >
                  Centimeters
                </MenuItem>

                <MenuItem
                  value="in"
                >
                  Inches
                </MenuItem>

                <MenuItem
                  value="px"
                >
                  Pixels
                </MenuItem>
              </Select>
            </FormControl>
          </>
        )}

        {hasBackSide && (
          <>
            <Divider />

            <Typography
              variant="subtitle2"
              fontWeight={700}
            >
              Card Side
            </Typography>

            <Stack
              direction="row"
              spacing={1}
            >
              <Button
                size="small"
                variant={
                  activeSide ===
                    'front'
                    ? 'contained'
                    : 'outlined'
                }
                onClick={() =>
                  void onSideChange(
                    'front',
                  )
                }
              >
                Front
              </Button>

              <Button
                size="small"
                variant={
                  activeSide ===
                    'back'
                    ? 'contained'
                    : 'outlined'
                }
                onClick={() =>
                  void onSideChange(
                    'back',
                  )
                }
              >
                Back
              </Button>
            </Stack>
          </>
        )}

        <Alert
          severity="info"
          icon={false}
        >
          <strong>
            {paper.width}
            {' × '}
            {paper.height}
            {' '}
            {paper.unit}
          </strong>

          <br />

          Canvas:{' '}
          {canvasSize.width}
          {' × '}
          {canvasSize.height}
          {' px'}

          <br />

          Side:{' '}
          <strong>
            {
              activeSide ===
                'front'
                ? 'Front'
                : 'Back'
            }
          </strong>
        </Alert>

        <Button
          variant="contained"
          disabled={
            !canvas
          }
          onClick={
            apply
          }
        >
          Apply Document Setup
        </Button>
      </Stack>
    </Box>
  )
}