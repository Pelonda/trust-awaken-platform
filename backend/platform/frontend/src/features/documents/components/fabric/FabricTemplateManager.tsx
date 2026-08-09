import {
  Alert,
  Button,
  CircularProgress,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  IconButton,
  List,
  ListItemButton,
  ListItemText,
  Snackbar,
  Stack,
  TextField,
  Tooltip,
} from '@mui/material'

import AddIcon from '@mui/icons-material/Add'
import SaveIcon from '@mui/icons-material/Save'
import FolderOpenIcon from '@mui/icons-material/FolderOpen'
import ContentCopyIcon from '@mui/icons-material/ContentCopy'
import DeleteIcon from '@mui/icons-material/Delete'
import StarIcon from '@mui/icons-material/Star'
import StarBorderIcon from '@mui/icons-material/StarBorder'

import {
  useEffect,
  useState,
} from 'react'

import type {
  Canvas,
} from 'fabric'

import FabricTemplateService, {
  type FabricCanvasJSON,
  type SavedFabricTemplate,
} from '../../services/FabricTemplateService'

interface Props {
  canvas: Canvas | null

  onNew(): void

  onLoaded(
    template: SavedFabricTemplate,
  ): void
}

const CUSTOM_PROPERTIES = [
  'awakenType',
  'awakenVariable',
  'awakenProtected',
  'awakenBrandAsset',
  'awakenAssetUuid',
  'awakenAssetPath',
]

export default function FabricTemplateManager({
  canvas,
  onNew,
  onLoaded,
}: Props) {
  const [
    templateName,
    setTemplateName,
  ] = useState(
    'Untitled Template',
  )

  const [
    currentTemplateId,
    setCurrentTemplateId,
  ] = useState<number | null>(
    null,
  )

  const [
    templates,
    setTemplates,
  ] = useState<
    SavedFabricTemplate[]
  >([])

  const [
    dialogOpen,
    setDialogOpen,
  ] = useState(false)

  const [
    loading,
    setLoading,
  ] = useState(false)

  const [
    saving,
    setSaving,
  ] = useState(false)

  const [
    message,
    setMessage,
  ] = useState<string | null>(
    null,
  )

  const [
    error,
    setError,
  ] = useState<string | null>(
    null,
  )

  async function refresh() {
    try {
      const result =
        await FabricTemplateService.all()

      setTemplates(
        result,
      )
    } catch (exception) {
      console.error(
        exception,
      )

      setError(
        exception instanceof Error
          ? exception.message
          : 'Unable to retrieve templates.',
      )
    }
  }

  useEffect(() => {
    void refresh()
  }, [])

  async function save() {
    if (!canvas) {
      return
    }

    const name =
      templateName.trim()

    if (!name) {
      setError(
        'Enter a template name.',
      )

      return
    }

    try {
      setSaving(true)
      setError(null)

      /*
       * Persist Fabric JSON plus all
       * Trust AWAKEN-specific metadata.
       *
       * awakenAssetUuid / awakenAssetPath
       * allow templates to reference
       * permanent document_assets instead
       * of storing base64 images.
       */
      const json =
        canvas.toJSON(
          CUSTOM_PROPERTIES,
        ) as FabricCanvasJSON

      const saved =
        await FabricTemplateService.save(
          name,
          json,
          currentTemplateId,
        )

      setCurrentTemplateId(
        saved.id,
      )

      setTemplateName(
        saved.name,
      )

      setMessage(
        currentTemplateId
          ? `Template "${saved.name}" updated.`
          : `Template "${saved.name}" created.`,
      )

      await refresh()
    } catch (exception) {
      console.error(
        exception,
      )

      setError(
        exception instanceof Error
          ? exception.message
          : 'Unable to save template.',
      )
    } finally {
      setSaving(false)
    }
  }

  async function load(
    template:
      SavedFabricTemplate,
  ) {
    if (!canvas) {
      return
    }

    try {
      setLoading(true)
      setError(null)

      const loaded =
        await FabricTemplateService.get(
          template.id,
        )

      canvas.discardActiveObject()

      canvas.clear()

      await canvas.loadFromJSON(
        loaded.canvas,
      )

      canvas.requestRenderAll()

      setCurrentTemplateId(
        loaded.id,
      )

      setTemplateName(
        loaded.name,
      )

      setDialogOpen(
        false,
      )

      onLoaded(
        loaded,
      )

      setMessage(
        `Template "${loaded.name}" loaded.`,
      )
    } catch (exception) {
      console.error(
        exception,
      )

      setError(
        exception instanceof Error
          ? exception.message
          : 'Unable to load template.',
      )
    } finally {
      setLoading(false)
    }
  }

  function createNew() {
    setCurrentTemplateId(
      null,
    )

    setTemplateName(
      'Untitled Template',
    )

    setDialogOpen(
      false,
    )

    onNew()

    setMessage(
      'New template started.',
    )
  }

  async function duplicate(
    template:
      SavedFabricTemplate,
  ) {
    try {
      setLoading(true)
      setError(null)

      const copy =
        await FabricTemplateService.duplicate(
          template,
        )

      await refresh()

      setMessage(
        `"${copy.name}" created.`,
      )
    } catch (exception) {
      console.error(
        exception,
      )

      setError(
        exception instanceof Error
          ? exception.message
          : 'Unable to duplicate template.',
      )
    } finally {
      setLoading(false)
    }
  }

  async function remove(
    template:
      SavedFabricTemplate,
  ) {
    const confirmed =
      window.confirm(
        `Delete "${template.name}"?`,
      )

    if (!confirmed) {
      return
    }

    try {
      setLoading(true)
      setError(null)

      await FabricTemplateService.remove(
        template.id,
      )

      if (
        currentTemplateId ===
        template.id
      ) {
        setCurrentTemplateId(
          null,
        )

        setTemplateName(
          'Untitled Template',
        )

        onNew()
      }

      await refresh()

      setMessage(
        `"${template.name}" deleted.`,
      )
    } catch (exception) {
      console.error(
        exception,
      )

      setError(
        exception instanceof Error
          ? exception.message
          : 'Unable to delete template.',
      )
    } finally {
      setLoading(false)
    }
  }

  async function makeDefault(
    template:
      SavedFabricTemplate,
  ) {
    try {
      setLoading(true)
      setError(null)

      await FabricTemplateService.setDefault(
        template.id,
      )

      await refresh()

      setMessage(
        `"${template.name}" set as default.`,
      )
    } catch (exception) {
      console.error(
        exception,
      )

      setError(
        exception instanceof Error
          ? exception.message
          : 'Unable to set default template.',
      )
    } finally {
      setLoading(false)
    }
  }

  return (
    <>
      <Stack
        direction="row"
        spacing={1}
        alignItems="center"
      >
        <TextField
          size="small"
          label="Template name"
          value={
            templateName
          }
          onChange={
            event =>
              setTemplateName(
                event.target.value,
              )
          }
          sx={{
            width: 240,
          }}
        />

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <AddIcon />
          }
          onClick={
            createNew
          }
        >
          New
        </Button>

        <Button
          size="small"
          variant="contained"
          startIcon={
            saving
              ? (
                  <CircularProgress
                    size={16}
                    color="inherit"
                  />
                )
              : (
                  <SaveIcon />
                )
          }
          disabled={
            saving ||
            !canvas
          }
          onClick={() =>
            void save()
          }
        >
          {currentTemplateId
            ? 'Save Changes'
            : 'Save'}
        </Button>

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <FolderOpenIcon />
          }
          onClick={() => {
            setDialogOpen(
              true,
            )

            void refresh()
          }}
        >
          Templates
        </Button>
      </Stack>

      <Dialog
        open={
          dialogOpen
        }
        onClose={() =>
          setDialogOpen(
            false,
          )
        }
        fullWidth
        maxWidth="sm"
      >
        <DialogTitle>
          Document Templates
        </DialogTitle>

        <DialogContent
          dividers
        >
          {loading ? (
            <Stack
              alignItems="center"
              sx={{
                py: 4,
              }}
            >
              <CircularProgress />
            </Stack>
          ) : templates.length ===
            0 ? (
            <Alert
              severity="info"
            >
              No templates yet.
            </Alert>
          ) : (
            <List
              disablePadding
            >
              {templates.map(
                template => (
                  <ListItemButton
                    key={
                      template.id
                    }
                    selected={
                      currentTemplateId ===
                      template.id
                    }
                    onClick={() =>
                      void load(
                        template,
                      )
                    }
                  >
                    <ListItemText
                      primary={
                        template.name
                      }
                      secondary={
                        `${template.type} • ${template.paper_size} • ${template.orientation}`
                      }
                    />

                    <Stack
                      direction="row"
                      onClick={
                        event =>
                          event.stopPropagation()
                      }
                    >
                      <Tooltip
                        title={
                          template.default
                            ? 'Default'
                            : 'Set default'
                        }
                      >
                        <span>
                          <IconButton
                            size="small"
                            disabled={
                              template.default
                            }
                            onClick={() =>
                              void makeDefault(
                                template,
                              )
                            }
                          >
                            {template.default ? (
                              <StarIcon
                                color="warning"
                                fontSize="small"
                              />
                            ) : (
                              <StarBorderIcon
                                fontSize="small"
                              />
                            )}
                          </IconButton>
                        </span>
                      </Tooltip>

                      <Tooltip
                        title="Duplicate"
                      >
                        <IconButton
                          size="small"
                          onClick={() =>
                            void duplicate(
                              template,
                            )
                          }
                        >
                          <ContentCopyIcon
                            fontSize="small"
                          />
                        </IconButton>
                      </Tooltip>

                      <Tooltip
                        title="Delete"
                      >
                        <IconButton
                          size="small"
                          color="error"
                          onClick={() =>
                            void remove(
                              template,
                            )
                          }
                        >
                          <DeleteIcon
                            fontSize="small"
                          />
                        </IconButton>
                      </Tooltip>
                    </Stack>
                  </ListItemButton>
                ),
              )}
            </List>
          )}
        </DialogContent>

        <DialogActions>
          <Button
            startIcon={
              <AddIcon />
            }
            onClick={
              createNew
            }
          >
            New Template
          </Button>

          <Button
            onClick={() =>
              setDialogOpen(
                false,
              )
            }
          >
            Close
          </Button>
        </DialogActions>
      </Dialog>

      <Snackbar
        open={
          Boolean(
            message,
          )
        }
        autoHideDuration={
          3000
        }
        message={
          message
        }
        onClose={() =>
          setMessage(
            null,
          )
        }
      />

      <Snackbar
        open={
          Boolean(
            error,
          )
        }
        autoHideDuration={
          6000
        }
        onClose={() =>
          setError(
            null,
          )
        }
      >
        <Alert
          severity="error"
          onClose={() =>
            setError(
              null,
            )
          }
        >
          {error}
        </Alert>
      </Snackbar>
    </>
  )
}