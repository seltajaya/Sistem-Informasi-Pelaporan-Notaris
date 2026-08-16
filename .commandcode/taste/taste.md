# Taste

## Communication
- Communicates in Bahasa Indonesia; expects assistant responses in Indonesian as well. Confidence: 0.8

## Workflow
- Before making changes or discussing a project, wants the assistant to first thoroughly study/explore the codebase structure and give a comprehensive summary (stack, architecture, entities, routes, auth, features). Confidence: 0.8
- Also wants the assistant to read and study the project's "second brain" — planning/spec documents in the repo (e.g., `plan_*.md`, `docs/superpowers/plans` & `specs`) and the external Obsidian vault at `C:\Workspace\Obsidian\Nexus-Brain\00_Second_Brain` (structured with `README.md`, `_AI_INDEX.md`, `00_Inbox`, `30_References`, `40_Learnings`, `50_MOCs`, `60_SessionLogs`) — and summarize what has been implemented vs. planned. Confidence: 0.8
- Wants the assistant to actively maintain the Obsidian second brain: write session logs into `60_SessionLogs/` following the vault's templates (in `_Templates/`) and update `_AI_INDEX.md` with new entries when a session produces notable work. Confidence: 0.7
- Records an ethical boundary: does not want the assistant to build tools that access systems without authorization (e.g., a "check acceptance status" tool hitting the Kemnaker system). Confidence: 0.6
