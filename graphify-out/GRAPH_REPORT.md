# Graph Report - hw14BackProyectWebPHP  (2026-06-01)

## Corpus Check
- 11 files · ~1,432 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 42 nodes · 51 edges · 11 communities (3 shown, 8 thin omitted)
- Extraction: 65% EXTRACTED · 35% INFERRED · 0% AMBIGUOUS · INFERRED: 18 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `75340981`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- [[_COMMUNITY_Community 0|Community 0]]
- [[_COMMUNITY_Community 1|Community 1]]
- [[_COMMUNITY_Community 2|Community 2]]
- [[_COMMUNITY_Community 3|Community 3]]
- [[_COMMUNITY_Community 4|Community 4]]
- [[_COMMUNITY_Community 5|Community 5]]
- [[_COMMUNITY_Community 6|Community 6]]
- [[_COMMUNITY_Community 7|Community 7]]

## God Nodes (most connected - your core abstractions)
1. `Response` - 12 edges
2. `UserController` - 9 edges
3. `User` - 8 edges
4. `UserRepository` - 8 edges
5. `SupportController` - 3 edges
6. `Router` - 3 edges
7. `DashboardController` - 2 edges
8. `Database` - 2 edges
9. `SupportTicket` - 2 edges

## Surprising Connections (you probably didn't know these)
- None detected - all connections are within the same source files.

## Communities (11 total, 8 thin omitted)

## Knowledge Gaps
- **8 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Response` connect `Community 1` to `Community 0`, `Community 2`, `Community 4`, `Community 6`?**
  _High betweenness centrality (0.549) - this node is a cross-community bridge._
- **Why does `User` connect `Community 3` to `Community 8`, `Community 2`, `Community 5`?**
  _High betweenness centrality (0.337) - this node is a cross-community bridge._
- **Why does `UserController` connect `Community 0` to `Community 1`?**
  _High betweenness centrality (0.097) - this node is a cross-community bridge._
- **Are the 10 inferred relationships involving `Response` (e.g. with `.getSummary()` and `.create()`) actually correct?**
  _`Response` has 10 INFERRED edges - model-reasoned connections that need verification._
- **Are the 7 inferred relationships involving `User` (e.g. with `.create()` and `.findByEmail()`) actually correct?**
  _`User` has 7 INFERRED edges - model-reasoned connections that need verification._